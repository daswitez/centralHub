<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    /**
     * Lista de vehículos
     */
    public function index(Request $request): View
    {
        $estado = $request->get('estado', '');
        $tipo = $request->get('tipo', '');

        $query = DB::table('cat.vehiculo as v')
            ->select([
                'v.*',
                't.nombre as conductor_nombre',
                't.codigo_transp as conductor_codigo'
            ])
            ->leftJoin('cat.transportista as t', 't.vehiculo_asignado_id', '=', 'v.vehiculo_id');

        if ($estado) {
            $query->where('v.estado', $estado);
        }
        if ($tipo) {
            $query->where('v.tipo', $tipo);
        }

        $vehiculos = $query->orderBy('v.codigo_vehiculo')->paginate(15);

        // Opciones para filtros
        $estados = ['DISPONIBLE', 'EN_USO', 'MANTENIMIENTO', 'FUERA_SERVICIO'];
        $tipos = ['CAMION', 'FURGON', 'REFRIGERADO', 'CISTERNA'];

        return view('cat.vehiculos.index', compact('vehiculos', 'estados', 'tipos', 'estado', 'tipo'));
    }

    /**
     * Formulario para crear vehículo
     */
    public function create(): View
    {
        $tipos = ['CAMION', 'FURGON', 'REFRIGERADO', 'CISTERNA'];
        return view('cat.vehiculos.create', compact('tipos'));
    }

    /**
     * Guardar nuevo vehículo
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_vehiculo' => 'required|string|max:20|unique:cat.vehiculo,codigo_vehiculo',
            'placa' => 'required|string|max:15|unique:cat.vehiculo,placa',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'anio' => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:30',
            'capacidad_t' => 'required|numeric|min:0.1',
            'tipo' => 'required|string|max:30',
            'kilometraje' => 'nullable|integer|min:0'
        ]);

        $validated['estado'] = 'DISPONIBLE';

        DB::table('cat.vehiculo')->insert($validated);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo registrado exitosamente');
    }

    /**
     * Ver detalles del vehículo
     */
    public function show(int $id): View
    {
        $vehiculo = DB::table('cat.vehiculo as v')
            ->select([
                'v.*',
                't.nombre as conductor_nombre',
                't.telefono as conductor_telefono',
                't.codigo_transp as conductor_codigo'
            ])
            ->leftJoin('cat.transportista as t', 't.vehiculo_asignado_id', '=', 'v.vehiculo_id')
            ->where('v.vehiculo_id', $id)
            ->first();

        if (!$vehiculo) {
            abort(404);
        }

        // Historial de envíos con este vehículo
        $envios = DB::table('logistica.envio as e')
            ->select(['e.codigo_envio', 'e.fecha_salida', 'e.estado', 't.nombre as conductor'])
            ->leftJoin('cat.transportista as t', 't.transportista_id', '=', 'e.transportista_id')
            ->where('e.vehiculo_id', $id)
            ->orderByDesc('e.fecha_salida')
            ->limit(10)
            ->get();

        return view('cat.vehiculos.show', compact('vehiculo', 'envios'));
    }

    /**
     * Formulario para editar
     */
    public function edit(int $id): View
    {
        $vehiculo = DB::table('cat.vehiculo')->where('vehiculo_id', $id)->first();
        
        if (!$vehiculo) {
            abort(404);
        }

        $tipos = ['CAMION', 'FURGON', 'REFRIGERADO', 'CISTERNA'];
        $estados = ['DISPONIBLE', 'EN_USO', 'MANTENIMIENTO', 'FUERA_SERVICIO'];

        return view('cat.vehiculos.edit', compact('vehiculo', 'tipos', 'estados'));
    }

    /**
     * Actualizar vehículo
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'anio' => 'nullable|integer',
            'color' => 'nullable|string|max:30',
            'capacidad_t' => 'required|numeric|min:0.1',
            'tipo' => 'required|string|max:30',
            'estado' => 'required|string|max:20',
            'kilometraje' => 'nullable|integer|min:0',
            'fecha_ultima_revision' => 'nullable|date',
            'fecha_proxima_revision' => 'nullable|date',
            'vencimiento_seguro' => 'nullable|date',
            'vencimiento_inspeccion' => 'nullable|date'
        ]);

        DB::table('cat.vehiculo')
            ->where('vehiculo_id', $id)
            ->update($validated);

        return redirect()->route('vehiculos.show', $id)
            ->with('success', 'Vehículo actualizado exitosamente');
    }

    /**
     * Asignar conductor a vehículo
     */
    public function asignarConductor(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'transportista_id' => 'required|integer'
        ]);

        // Quitar asignación anterior del conductor
        DB::table('cat.transportista')
            ->where('vehiculo_asignado_id', $id)
            ->update(['vehiculo_asignado_id' => null]);

        // Asignar nuevo conductor
        DB::table('cat.transportista')
            ->where('transportista_id', $request->transportista_id)
            ->update(['vehiculo_asignado_id' => $id]);

        return redirect()->route('vehiculos.show', $id)
            ->with('success', 'Conductor asignado exitosamente');
    }
}
