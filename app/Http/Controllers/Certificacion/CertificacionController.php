<?php

namespace App\Http\Controllers\Certificacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CertificacionController extends Controller
{
    /**
     * Lista de certificaciones con filtros
     */
    public function index(Request $request): View
    {
        $ambito = $request->get('ambito', '');
        $estado = $request->get('estado', '');

        $query = "
            SELECT c.certificado_id, c.codigo_certificado, c.ambito, c.area, c.emisor, 
                   c.vigente_desde, c.vigente_hasta, c.url_archivo,
                   count(distinct cls.lote_salida_id) as num_lotes_salida,
                   count(distinct clp.lote_planta_id) as num_lotes_planta,
                   count(distinct clc.lote_campo_id) as num_lotes_campo,
                   count(distinct ce.envio_id) as num_envios,
                   CASE
                       WHEN c.vigente_hasta IS NULL THEN 'VIGENTE'
                       WHEN c.vigente_hasta >= current_date THEN 'VIGENTE'
                       WHEN c.vigente_hasta BETWEEN current_date - interval '30 days' AND current_date THEN 'POR_VENCER'
                       ELSE 'VENCIDO'
                   END as estado
            FROM certificacion.certificado c
            LEFT JOIN certificacion.certificadolotesalida cls ON cls.certificado_id = c.certificado_id
            LEFT JOIN certificacion.certificadoloteplanta clp ON clp.certificado_id = c.certificado_id
            LEFT JOIN certificacion.certificadolotecampo clc ON clc.certificado_id = c.certificado_id
            LEFT JOIN certificacion.certificadoenvio ce ON ce.certificado_id = c.certificado_id
            WHERE 1=1
        ";

        $params = [];
        
        if ($ambito) {
            $query .= " AND c.ambito = ?";
            $params[] = $ambito;
        }

        $query .= " GROUP BY c.certificado_id ORDER BY c.certificado_id DESC";

        $certificados = DB::select($query, $params);

        // Filtrar por estado después (porque es calculado)
        if ($estado) {
            $certificados = array_filter($certificados, fn($c) => $c->estado === $estado);
        }

        // KPIs
        $kpiVigentes = DB::selectOne("
            SELECT count(*) as c FROM certificacion.certificado 
            WHERE vigente_hasta IS NULL OR vigente_hasta >= current_date
        ");

        $kpiPorVencer = DB::selectOne("
            SELECT count(*) as c FROM certificacion.certificado 
            WHERE vigente_hasta BETWEEN current_date AND current_date + interval '30 days'
        ");

        $kpiVencidos = DB::selectOne("
            SELECT count(*) as c FROM certificacion.certificado 
            WHERE vigente_hasta < current_date
        ");

        $kpiGenerales = DB::selectOne("
            SELECT count(*) as c FROM certificacion.certificado 
            WHERE ambito = 'GENERAL'
        ");

        // Ámbitos disponibles
        $ambitos = ['CAMPO', 'PLANTA', 'SALIDA', 'ENVIO', 'GENERAL'];
        $areas = ['HACCP', 'ISO', 'BPM', 'BPA', 'GLOBAL_GAP'];

        return view('certificacion.index', [
            'certificados' => $certificados,
            'kpi_vigentes' => (int)($kpiVigentes->c ?? 0),
            'kpi_por_vencer' => (int)($kpiPorVencer->c ?? 0),
            'kpi_vencidos' => (int)($kpiVencidos->c ?? 0),
            'kpi_generales' => (int)($kpiGenerales->c ?? 0),
            'ambitos' => $ambitos,
            'areas' => $areas,
            'filtro_ambito' => $ambito,
            'filtro_estado' => $estado
        ]);
    }

    /**
     * Detalle de certificación con timeline de etapas
     */
    public function show(int $id): View
    {
        $certificado = DB::table('certificacion.certificado')
            ->where('certificado_id', $id)
            ->first();

        if (!$certificado) {
            abort(404);
        }

        // Obtener lotes de campo asociados
        $lotesCampo = DB::select("
            SELECT lc.*, v.nombre_comercial as variedad, p.nombre as productor
            FROM certificacion.certificadolotecampo clc
            JOIN campo.lotecampo lc ON lc.lote_campo_id = clc.lote_campo_id
            LEFT JOIN cat.variedadpapa v ON v.variedad_id = lc.variedad_id
            LEFT JOIN campo.productor p ON p.productor_id = lc.productor_id
            WHERE clc.certificado_id = ?
        ", [$id]);

        // Obtener lotes de planta asociados
        $lotesPlanta = DB::select("
            SELECT lp.*, pl.nombre as planta
            FROM certificacion.certificadoloteplanta clp
            JOIN planta.loteplanta lp ON lp.lote_planta_id = clp.lote_planta_id
            LEFT JOIN cat.planta pl ON pl.planta_id = lp.planta_id
            WHERE clp.certificado_id = ?
        ", [$id]);

        // Obtener lotes de salida asociados
        $lotesSalida = DB::select("
            SELECT ls.*, lp.codigo_lote_planta
            FROM certificacion.certificadolotesalida cls
            JOIN planta.lotesalida ls ON ls.lote_salida_id = cls.lote_salida_id
            LEFT JOIN planta.loteplanta lp ON lp.lote_planta_id = ls.lote_planta_id
            WHERE cls.certificado_id = ?
        ", [$id]);

        // Obtener envíos asociados
        $envios = DB::select("
            SELECT e.*, t.nombre as transportista, v.placa
            FROM certificacion.certificadoenvio ce
            JOIN logistica.envio e ON e.envio_id = ce.envio_id
            LEFT JOIN cat.transportista t ON t.transportista_id = e.transportista_id
            LEFT JOIN cat.vehiculo v ON v.vehiculo_id = e.vehiculo_id
            WHERE ce.certificado_id = ?
        ", [$id]);

        // Obtener evidencias
        $evidencias = DB::table('certificacion.certificadoevidencia')
            ->where('certificado_id', $id)
            ->orderBy('fecha_registro', 'desc')
            ->get();

        return view('certificacion.show', [
            'certificado' => $certificado,
            'lotes_campo' => $lotesCampo,
            'lotes_planta' => $lotesPlanta,
            'lotes_salida' => $lotesSalida,
            'envios' => $envios,
            'evidencias' => $evidencias
        ]);
    }

    /**
     * Subir evidencia documental
     */
    public function uploadEvidencia(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'tipo' => 'required|string|max:60',
            'descripcion' => 'nullable|string|max:400',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240' // Max 10MB
        ]);

        $archivo = $request->file('archivo');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $path = $archivo->storeAs('certificados/evidencias', $nombreArchivo, 'public');

        DB::table('certificacion.certificadoevidencia')->insert([
            'certificado_id' => $id,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'url_archivo' => '/storage/' . $path,
            'fecha_registro' => now()
        ]);

        return redirect()->route('certificaciones.show', $id)
            ->with('success', 'Documento subido correctamente');
    }

    /**
     * Eliminar evidencia
     */
    public function deleteEvidencia(int $id, int $evidenciaId): RedirectResponse
    {
        $evidencia = DB::table('certificacion.certificadoevidencia')
            ->where('evidencia_id', $evidenciaId)
            ->where('certificado_id', $id)
            ->first();

        if ($evidencia) {
            // Eliminar archivo físico si existe
            if ($evidencia->url_archivo) {
                $path = str_replace('/storage/', '', $evidencia->url_archivo);
                Storage::disk('public')->delete($path);
            }

            // Eliminar registro
            DB::table('certificacion.certificadoevidencia')
                ->where('evidencia_id', $evidenciaId)
                ->delete();
        }

        return redirect()->route('certificaciones.show', $id)
            ->with('success', 'Documento eliminado correctamente');
    }

    /**
     * Formulario para crear certificación
     */
    public function create(): View
    {
        // Lotes de campo sin certificar o para selección
        $lotesCampo = DB::select("
            SELECT lc.lote_campo_id, lc.codigo_lote_campo, lc.fecha_cosecha,
                   v.nombre_comercial as variedad, p.nombre as productor
            FROM campo.lotecampo lc
            LEFT JOIN cat.variedadpapa v ON v.variedad_id = lc.variedad_id
            LEFT JOIN campo.productor p ON p.productor_id = lc.productor_id
            ORDER BY lc.fecha_cosecha DESC
            LIMIT 50
        ");

        // Lotes de planta
        $lotesPlanta = DB::select("
            SELECT lp.lote_planta_id, lp.codigo_lote_planta, lp.fecha_inicio,
                   pl.nombre as planta
            FROM planta.loteplanta lp
            LEFT JOIN cat.planta pl ON pl.planta_id = lp.planta_id
            ORDER BY lp.fecha_inicio DESC
            LIMIT 50
        ");

        // Lotes de salida
        $lotesSalida = DB::select("
            SELECT ls.lote_salida_id, ls.codigo_lote_salida, ls.sku, ls.fecha_empaque,
                   lp.codigo_lote_planta
            FROM planta.lotesalida ls
            LEFT JOIN planta.loteplanta lp ON lp.lote_planta_id = ls.lote_planta_id
            ORDER BY ls.fecha_empaque DESC
            LIMIT 50
        ");

        // Envíos
        $envios = DB::select("
            SELECT e.envio_id, e.codigo_envio, e.estado, e.fecha_salida
            FROM logistica.envio e
            ORDER BY e.fecha_salida DESC
            LIMIT 50
        ");

        $ambitos = ['CAMPO', 'PLANTA', 'SALIDA', 'ENVIO', 'GENERAL'];
        $areas = ['HACCP', 'ISO', 'BPM', 'BPA', 'GLOBAL_GAP'];

        return view('certificacion.create', [
            'lotes_campo' => $lotesCampo,
            'lotes_planta' => $lotesPlanta,
            'lotes_salida' => $lotesSalida,
            'envios' => $envios,
            'ambitos' => $ambitos,
            'areas' => $areas
        ]);
    }

    /**
     * Guardar nueva certificación
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ambito' => 'required|in:CAMPO,PLANTA,SALIDA,ENVIO,GENERAL',
            'area' => 'required|in:HACCP,ISO,BPM,BPA,GLOBAL_GAP',
            'emisor' => 'required|string|max:160',
            'vigente_desde' => 'required|date',
            'vigente_hasta' => 'nullable|date|after:vigente_desde',
            'lotes_campo' => 'nullable|array',
            'lotes_planta' => 'nullable|array',
            'lotes_salida' => 'nullable|array',
            'envios' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            // Generar código único
            $ultimoId = DB::table('certificacion.certificado')->max('certificado_id') ?? 0;
            $codigo = 'CERT-' . $validated['ambito'] . '-' . date('Y') . '-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);

            // Insertar certificado
            $certId = DB::table('certificacion.certificado')->insertGetId([
                'codigo_certificado' => $codigo,
                'ambito' => $validated['ambito'],
                'area' => $validated['area'],
                'emisor' => $validated['emisor'],
                'vigente_desde' => $validated['vigente_desde'],
                'vigente_hasta' => $validated['vigente_hasta'] ?? null
            ], 'certificado_id');

            // Asociar lotes de campo
            if (!empty($validated['lotes_campo'])) {
                foreach ($validated['lotes_campo'] as $loteId) {
                    DB::table('certificacion.certificadolotecampo')->insert([
                        'certificado_id' => $certId,
                        'lote_campo_id' => $loteId
                    ]);
                }
            }

            // Asociar lotes de planta
            if (!empty($validated['lotes_planta'])) {
                foreach ($validated['lotes_planta'] as $loteId) {
                    DB::table('certificacion.certificadoloteplanta')->insert([
                        'certificado_id' => $certId,
                        'lote_planta_id' => $loteId
                    ]);
                }
            }

            // Asociar lotes de salida
            if (!empty($validated['lotes_salida'])) {
                foreach ($validated['lotes_salida'] as $loteId) {
                    DB::table('certificacion.certificadolotesalida')->insert([
                        'certificado_id' => $certId,
                        'lote_salida_id' => $loteId
                    ]);
                }
            }

            // Asociar envíos
            if (!empty($validated['envios'])) {
                foreach ($validated['envios'] as $envioId) {
                    DB::table('certificacion.certificadoenvio')->insert([
                        'certificado_id' => $certId,
                        'envio_id' => $envioId
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('certificaciones.show', $certId)
                ->with('success', "Certificado {$codigo} creado exitosamente");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear certificado: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Verificar si se pueden emitir certificación general para un lote de salida
     */
    public function verificarCadena(int $loteSalidaId)
    {
        // Obtener la cadena completa desde el lote de salida
        $cadena = $this->obtenerCadenaCompleta($loteSalidaId);

        // Verificar certificaciones de cada etapa
        $estadoEtapas = [];

        // Campo
        if (!empty($cadena['lotes_campo'])) {
            $lotesCampoIds = implode(',', $cadena['lotes_campo']);
            $certCampo = DB::selectOne("
                SELECT count(*) as c FROM certificacion.certificadolotecampo clc
                JOIN certificacion.certificado c ON c.certificado_id = clc.certificado_id
                WHERE clc.lote_campo_id IN ({$lotesCampoIds})
                AND (c.vigente_hasta IS NULL OR c.vigente_hasta >= current_date)
            ");
            $estadoEtapas['campo'] = [
                'completo' => $certCampo->c > 0,
                'lotes' => $cadena['lotes_campo']
            ];
        }

        // Planta
        if (!empty($cadena['lotes_planta'])) {
            $lotesPlantaIds = implode(',', $cadena['lotes_planta']);
            $certPlanta = DB::selectOne("
                SELECT count(*) as c FROM certificacion.certificadoloteplanta clp
                JOIN certificacion.certificado c ON c.certificado_id = clp.certificado_id
                WHERE clp.lote_planta_id IN ({$lotesPlantaIds})
                AND (c.vigente_hasta IS NULL OR c.vigente_hasta >= current_date)
            ");
            $estadoEtapas['planta'] = [
                'completo' => $certPlanta->c > 0,
                'lotes' => $cadena['lotes_planta']
            ];
        }

        // Salida
        $certSalida = DB::selectOne("
            SELECT count(*) as c FROM certificacion.certificadolotesalida cls
            JOIN certificacion.certificado c ON c.certificado_id = cls.certificado_id
            WHERE cls.lote_salida_id = ?
            AND (c.vigente_hasta IS NULL OR c.vigente_hasta >= current_date)
        ", [$loteSalidaId]);
        $estadoEtapas['salida'] = [
            'completo' => $certSalida->c > 0,
            'lote' => $loteSalidaId
        ];

        // Envío
        if (!empty($cadena['envios'])) {
            $envioIds = implode(',', $cadena['envios']);
            $certEnvio = DB::selectOne("
                SELECT count(*) as c FROM certificacion.certificadoenvio ce
                JOIN certificacion.certificado c ON c.certificado_id = ce.certificado_id
                WHERE ce.envio_id IN ({$envioIds})
                AND (c.vigente_hasta IS NULL OR c.vigente_hasta >= current_date)
            ");
            $estadoEtapas['envio'] = [
                'completo' => $certEnvio->c > 0,
                'envios' => $cadena['envios']
            ];
        }

        // Determinar si se puede emitir general
        $todasCompletas = true;
        foreach ($estadoEtapas as $etapa) {
            if (!$etapa['completo']) {
                $todasCompletas = false;
                break;
            }
        }

        return response()->json([
            'puede_emitir_general' => $todasCompletas,
            'etapas' => $estadoEtapas,
            'cadena' => $cadena
        ]);
    }

    /**
     * Obtener cadena completa desde un lote de salida
     */
    private function obtenerCadenaCompleta(int $loteSalidaId): array
    {
        $cadena = [
            'lote_salida' => $loteSalidaId,
            'lotes_planta' => [],
            'lotes_campo' => [],
            'envios' => []
        ];

        // Obtener lote de planta
        $loteSalida = DB::table('planta.lotesalida')
            ->where('lote_salida_id', $loteSalidaId)
            ->first();

        if ($loteSalida && $loteSalida->lote_planta_id) {
            $cadena['lotes_planta'][] = $loteSalida->lote_planta_id;

            // Obtener lotes de campo asociados al lote de planta
            $lotesCampo = DB::table('planta.loteplanta_entradacampo')
                ->where('lote_planta_id', $loteSalida->lote_planta_id)
                ->pluck('lote_campo_id')
                ->toArray();

            $cadena['lotes_campo'] = $lotesCampo;
        }

        // Obtener envíos asociados
        $envios = DB::table('logistica.enviodetalle as ed')
            ->join('logistica.envio as e', 'e.envio_id', '=', 'ed.envio_id')
            ->where('ed.lote_salida_id', $loteSalidaId)
            ->pluck('e.envio_id')
            ->toArray();

        $cadena['envios'] = $envios;

        return $cadena;
    }

    /**
     * Exportar certificado a PDF
     */
    public function exportPdf(int $id)
    {
        $certificado = DB::table('certificacion.certificado')
            ->where('certificado_id', $id)
            ->first();

        if (!$certificado) {
            abort(404);
        }

        // Obtener lotes asociados
        $lotesCampo = DB::select("
            SELECT lc.codigo_lote_campo, v.nombre_comercial as variedad
            FROM certificacion.certificadolotecampo clc
            JOIN campo.lotecampo lc ON lc.lote_campo_id = clc.lote_campo_id
            LEFT JOIN cat.variedadpapa v ON v.variedad_id = lc.variedad_id
            WHERE clc.certificado_id = ?
        ", [$id]);

        $lotesPlanta = DB::select("
            SELECT lp.codigo_lote_planta, pl.nombre as planta
            FROM certificacion.certificadoloteplanta clp
            JOIN planta.loteplanta lp ON lp.lote_planta_id = clp.lote_planta_id
            LEFT JOIN cat.planta pl ON pl.planta_id = lp.planta_id
            WHERE clp.certificado_id = ?
        ", [$id]);

        $lotesSalida = DB::select("
            SELECT ls.codigo_lote_salida, ls.sku
            FROM certificacion.certificadolotesalida cls
            JOIN planta.lotesalida ls ON ls.lote_salida_id = cls.lote_salida_id
            WHERE cls.certificado_id = ?
        ", [$id]);

        $pdf = Pdf::loadView('pdf.certificado', [
            'certificado' => $certificado,
            'lotes_campo' => $lotesCampo,
            'lotes_planta' => $lotesPlanta,
            'lotes_salida' => $lotesSalida
        ]);

        $pdf->setPaper('letter', 'portrait');

        return $pdf->download("certificado_{$certificado->codigo_certificado}.pdf");
    }
}
