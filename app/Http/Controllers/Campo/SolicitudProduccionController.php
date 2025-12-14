<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use App\Services\MicroserviceClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Exception;

class SolicitudProduccionController extends Controller
{
    protected MicroserviceClient $orgtrackClient;

    public function __construct()
    {
        $this->orgtrackClient = new MicroserviceClient('orgtrack');
    }

    /**
     * Listado de solicitudes/envíos desde OrgTrack
     * 
     * Nota: La vista ahora carga los datos con JavaScript (fetch)
     * para que sean visibles en el Network tab del navegador
     */
    public function index(): View
    {
        // La vista cargará los datos con JavaScript
        return view('campo.solicitudes.index');
    }

    /**
     * Formulario para crear nueva solicitud
     * 
     * Nota: Esta vista ya fue limpiada y solo muestra mensaje informativo
     */
    public function create(): View
    {
        // Vista limpiada - solo muestra mensaje informativo
        return view('campo.solicitudes.create');
    }

    /**
     * Guardar nueva solicitud
     * 
     * Nota: Ahora crea un envío en OrgTrack en lugar de guardar en BD local
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_remitente' => 'required|string|max:255',
            'telefono_remitente' => 'required|string|max:20',
            'email_remitente' => 'required|email|max:255',
            'direccion_origen' => 'required|string|max:500',
            'direccion_destino' => 'required|string|max:500',
            'origen_lat' => 'required|numeric',
            'origen_lng' => 'required|numeric',
            'destino_lat' => 'required|numeric',
            'destino_lng' => 'required|numeric',
            'fecha_requerida' => 'required|date|after:today',
            'observaciones' => 'nullable|string|max:500',
            'productos' => 'required|array|min:1',
        ]);

        try {
            // 1. Crear dirección en OrgTrack
            $direccionData = [
                'nombreorigen' => $validated['direccion_origen'],
                'nombredestino' => $validated['direccion_destino'],
                'origen_lat' => $validated['origen_lat'],
                'origen_lng' => $validated['origen_lng'],
                'destino_lat' => $validated['destino_lat'],
                'destino_lng' => $validated['destino_lng'],
                'rutageojson' => null,
            ];

            $direccionResponse = $this->orgtrackClient->post('/publico/direccion', $direccionData);
            $idDireccion = $direccionResponse['id_direccion'];

            // 2. Crear envío en OrgTrack
            $envioData = [
                'nombre_remitente' => $validated['nombre_remitente'],
                'telefono_remitente' => $validated['telefono_remitente'],
                'email_remitente' => $validated['email_remitente'],
                'id_direccion' => $idDireccion,
                'particiones' => $this->construirParticiones($validated['productos']),
            ];

            // Agregar datos opcionales de recogida/entrega si están presentes
            if (isset($validated['fecha_requerida'])) {
                $envioData['particiones'][0]['recogidaEntrega'] = [
                    'fecha_recogida' => $validated['fecha_requerida'],
                    'hora_recogida' => '08:00',
                    'hora_entrega' => '17:00',
                    'instrucciones_recogida' => $validated['observaciones'] ?? '',
                    'instrucciones_entrega' => '',
                ];
            }

            $envioResponse = $this->orgtrackClient->post('/publico/envio', $envioData);

            return redirect()
                ->route('solicitudes.index')
                ->with('success', "Solicitud creada exitosamente en OrgTrack (ID: {$envioResponse['id_envio']})");

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear solicitud en OrgTrack: ' . $e->getMessage()]);
        }
    }

    /**
     * Listado de solicitudes recibidas (vista para productores)
     * 
     * Nota: Muestra los mismos datos que index() pero con filtro diferente
     */
    public function misSolicitudes(): View
    {
        try {
            // Consumir endpoint de envíos entregados de OrgTrack
            $envios = $this->orgtrackClient->get('/publico/envios-productores');

            // Transformar datos
            $solicitudes = collect($envios)->map(function ($envio) {
                return (object) [
                    'solicitud_id' => $envio['id'],
                    'codigo_solicitud' => $envio['numero_solicitud'] ?? "ENV-{$envio['id']}",
                    'cantidad_solicitada_t' => $this->calcularPesoTotal($envio),
                    'fecha_necesaria' => $envio['fecha_requerida'] ?? null,
                    'fecha_solicitud' => $envio['fecha_creacion'],
                    'fecha_respuesta' => $envio['fecha_inicio'] ?? null,
                    'estado' => $this->mapearEstado($envio['estado']),
                    'observaciones' => $envio['observaciones_solicitud'] ?? '',
                    'justificacion_rechazo' => $envio['cancelado'] ? 'Envío cancelado' : null,
                    'planta_nombre' => $envio['direccion_destino'] ?? 'N/A',
                    'variedad_nombre' => $this->extraerVariedad($envio),
                    'conductor_asignado' => $this->extraerConductor($envio),
                ];
            })
                ->sortBy(function ($solicitud) {
                    // Ordenar: pendientes primero, luego por fecha necesaria
                    return [$solicitud->estado === 'PENDIENTE' ? 0 : 1, $solicitud->fecha_necesaria];
                })
                ->values()
                ->all();

            return view('campo.solicitudes.mis_solicitudes', compact('solicitudes'));

        } catch (Exception $e) {
            $solicitudes = [];
            return view('campo.solicitudes.mis_solicitudes', compact('solicitudes'))
                ->with('error', 'Error al cargar solicitudes desde OrgTrack: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalle de solicitud/envío
     */
    public function show($id): View
    {
        try {
            // Obtener envío específico de OrgTrack
            $envio = $this->orgtrackClient->get("/publico/envio/{$id}");

            // Transformar a formato esperado por la vista
            $solicitud = (object) [
                'solicitud_id' => $envio['id'],
                'codigo_solicitud' => $envio['numero_solicitud'] ?? "ENV-{$envio['id']}",
                'cantidad_solicitada_t' => $this->calcularPesoTotal($envio),
                'fecha_necesaria' => $envio['fecha_requerida'] ?? null,
                'fecha_solicitud' => $envio['fecha_creacion'],
                'fecha_respuesta' => $envio['fecha_inicio'] ?? null,
                'estado' => $this->mapearEstado($envio['estado']),
                'observaciones' => $envio['observaciones_solicitud'] ?? '',
                'justificacion_rechazo' => $envio['cancelado'] ? 'Envío cancelado' : null,

                // Datos de planta (destino)
                'planta_nombre' => $envio['nombre_destino'] ?? 'N/A',
                'codigo_planta' => null,

                // Datos de productor (remitente)
                'productor_nombre' => $envio['nombre_remitente'],
                'codigo_productor' => $envio['telefono_remitente'],

                // Datos de variedad (extraídos de cargas)
                'variedad_nombre' => $this->extraerVariedad($envio),
                'codigo_variedad' => null,

                // Datos de conductor/transportista
                'conductor_nombre' => $this->extraerConductor($envio),
                'conductor_telefono' => $this->extraerTelefonoConductor($envio),
                'estado_asignacion' => $this->extraerEstadoAsignacion($envio),
                'fecha_asignacion' => $this->extraerFechaAsignacion($envio),
                'fecha_inicio_ruta' => $envio['fecha_inicio'] ?? null,
                'fecha_completado' => $envio['fecha_entrega'] ?? null,
            ];

            return view('campo.solicitudes.show', compact('solicitud'));

        } catch (Exception $e) {
            abort(404, 'Solicitud no encontrada en OrgTrack: ' . $e->getMessage());
        }
    }

    /**
     * Responder a solicitud (Aceptar/Rechazar)
     * 
     * Nota: Esta funcionalidad ya no está disponible en las vistas limpias
     * Se mantiene el método por compatibilidad pero debería retornar error
     */
    public function responder(Request $request, $id): RedirectResponse
    {
        return redirect()
            ->route('solicitudes.show', $id)
            ->withErrors(['error' => 'Esta funcionalidad ha sido migrada a OrgTrack. Por favor, gestione las asignaciones desde el sistema OrgTrack.']);
    }

    // ========================================================================
    // Métodos auxiliares para transformación de datos
    // ========================================================================

    /**
     * Mapea el estado de OrgTrack al formato del sistema legacy
     */
    protected function mapearEstado(string $estadoOrgTrack): string
    {
        return match ($estadoOrgTrack) {
            'Pendiente' => 'PENDIENTE',
            'En curso' => 'ACEPTADA',
            'Finalizado' => 'COMPLETADA',
            default => 'PENDIENTE',
        };
    }

    /**
     * Calcula el peso total del envío desde las particiones
     */
    protected function calcularPesoTotal($envio): float
    {
        if (!isset($envio['particiones']) || !is_array($envio['particiones'])) {
            return 0.0;
        }

        $pesoTotal = 0;
        foreach ($envio['particiones'] as $particion) {
            if (isset($particion['cargas']) && is_array($particion['cargas'])) {
                foreach ($particion['cargas'] as $carga) {
                    $pesoTotal += $carga['peso'] ?? 0;
                }
            }
        }

        // Convertir de kg a toneladas
        return round($pesoTotal / 1000, 3);
    }

    /**
     * Extrae el nombre de la variedad desde las cargas
     */
    protected function extraerVariedad($envio): string
    {
        if (!isset($envio['particiones']) || !is_array($envio['particiones'])) {
            return 'N/A';
        }

        foreach ($envio['particiones'] as $particion) {
            if (isset($particion['cargas']) && is_array($particion['cargas']) && count($particion['cargas']) > 0) {
                // Retornar el nombre del primer producto encontrado
                return $particion['cargas'][0]['nombre_producto'] ?? 'N/A';
            }
        }

        return 'N/A';
    }

    /**
     * Extrae el nombre del conductor desde las particiones
     */
    protected function extraerConductor($envio): ?string
    {
        if (!isset($envio['particiones']) || !is_array($envio['particiones'])) {
            return null;
        }

        foreach ($envio['particiones'] as $particion) {
            if (isset($particion['transportista'])) {
                $t = $particion['transportista'];
                return ($t['nombre'] ?? '') . ' ' . ($t['apellido'] ?? '');
            }
        }

        return null;
    }

    /**
     * Extrae el teléfono del conductor
     */
    protected function extraerTelefonoConductor($envio): ?string
    {
        if (!isset($envio['particiones']) || !is_array($envio['particiones'])) {
            return null;
        }

        foreach ($envio['particiones'] as $particion) {
            if (isset($particion['transportista']['telefono'])) {
                return $particion['transportista']['telefono'];
            }
        }

        return null;
    }

    /**
     * Extrae el estado de asignación
     */
    protected function extraerEstadoAsignacion($envio): ?string
    {
        if (!isset($envio['particiones']) || !is_array($envio['particiones'])) {
            return null;
        }

        foreach ($envio['particiones'] as $particion) {
            if (isset($particion['estado'])) {
                return strtoupper($particion['estado']);
            }
        }

        return null;
    }

    /**
     * Extrae la fecha de asignación
     */
    protected function extraerFechaAsignacion($envio): ?string
    {
        if (!isset($envio['particiones']) || !is_array($envio['particiones'])) {
            return null;
        }

        foreach ($envio['particiones'] as $particion) {
            if (isset($particion['fecha_asignacion'])) {
                return $particion['fecha_asignacion'];
            }
        }

        return null;
    }

    /**
     * Construye el array de particiones para OrgTrack desde los productos
     */
    protected function construirParticiones(array $productos): array
    {
        $cargas = [];

        foreach ($productos as $producto) {
            $cargas[] = [
                'id_categoria' => $producto['id_categoria'] ?? 1,
                'id_producto' => $producto['id_producto'] ?? 1,
                'id_tipo_empaque' => $producto['id_tipo_empaque'] ?? 1,
                'cantidad' => $producto['cantidad'] ?? 0,
                'peso' => $producto['peso_kg'] ?? 0,
                'conteo_por_empaque' => $producto['conteo_por_empaque'] ?? 1,
                'peso_promedio_unidad' => $producto['peso_promedio_unidad'] ?? 0,
                'capacidad_por_empaque' => $producto['capacidad_por_empaque'] ?? 1,
                'largo_cm' => $producto['largo_cm'] ?? 0,
                'ancho_cm' => $producto['ancho_cm'] ?? 0,
                'alto_cm' => $producto['alto_cm'] ?? 0,
                'peso_neto_kg' => $producto['peso_neto_kg'] ?? 0,
                'tara_kg' => $producto['tara_kg'] ?? 0,
                'peso_bruto_kg' => $producto['peso_bruto_kg'] ?? 0,
                'forma_pedido' => $producto['forma_pedido'] ?? 'unidades',
                'cantidad_pedido' => $producto['cantidad_pedido'] ?? 0,
                'empaques_calculados' => $producto['empaques_calculados'] ?? 0,
                'unidades_por_pallet' => $producto['unidades_por_pallet'] ?? 0,
                'numero_pallets' => $producto['numero_pallets'] ?? 0,
            ];
        }

        return [
            [
                'id_tipo_transporte' => 1, // Por defecto
                'cargas' => $cargas,
            ]
        ];
    }
}
