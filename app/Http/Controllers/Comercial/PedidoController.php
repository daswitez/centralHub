<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PedidoController extends Controller
{
    /**
     * Mostrar listado de pedidos
     */
    public function index(): View
    {
        $pedidos = DB::select('
            SELECT p.pedido_id, p.codigo_pedido, p.fecha_pedido, p.estado,
                   c.nombre as cliente_nombre, c.codigo_cliente,
                   count(pd.pedido_detalle_id) as total_items,
                   sum(pd.cantidad_t * pd.precio_unit_usd) as monto_total
            FROM comercial.pedido p
            LEFT JOIN cat.cliente c ON c.cliente_id = p.cliente_id
            LEFT JOIN comercial.pedidodetalle pd ON pd.pedido_id = p.pedido_id
            GROUP BY p.pedido_id, p.codigo_pedido, p.fecha_pedido, p.estado, c.nombre, c.codigo_cliente
            ORDER BY p.fecha_pedido DESC
        ');
        
        return view('comercial.pedidos.index', compact('pedidos'));
    }

    /**
     * Mostrar formulario para crear pedido
     */
    public function create(): View
    {
        $clientes = DB::table('cat.cliente')
            ->orderBy('nombre')
            ->get();
            
        return view('comercial.pedidos.create', compact('clientes'));
    }

    /**
     * Guardar nuevo pedido
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_id' => 'required|integer',
            'fecha_pedido' => 'required|date',
            'observaciones' => 'nullable|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.sku' => 'required|string',
            'detalles.*.cantidad_t' => 'required|numeric|min:0.01',
            'detalles.*.precio_unit_usd' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // Generar código de pedido
            $ultimoPedido = DB::table('comercial.pedido')
                ->orderBy('pedido_id', 'desc')
                ->first();
            
            $numero = $ultimoPedido ? ((int) substr($ultimoPedido->codigo_pedido, -3)) + 1 : 1;
            $codigoPedido = 'PED-' . date('Y') . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT);

            // Insertar pedido - especificar la columna de secuencia correcta
            $pedidoId = DB::table('comercial.pedido')->insertGetId([
                'codigo_pedido' => $codigoPedido,
                'cliente_id' => $validated['cliente_id'],
                'fecha_pedido' => $validated['fecha_pedido'],
                'estado' => 'PENDIENTE',
            ], 'pedido_id'); // Especificar el nombre de la columna de auto-incremento

            // Insertar detalles
            foreach ($validated['detalles'] as $detalle) {
                DB::table('comercial.pedidodetalle')->insert([
                    'pedido_id' => $pedidoId,
                    'sku' => $detalle['sku'],
                    'cantidad_t' => $detalle['cantidad_t'],
                    'precio_unit_usd' => $detalle['precio_unit_usd'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('comercial.pedidos.index')
                ->with('success', "Pedido $codigoPedido registrado correctamente");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al registrar pedido: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar detalle de un pedido
     */
    public function show(int $id): View
    {
        $pedido = DB::selectOne('
            SELECT p.*, c.nombre as cliente_nombre, c.codigo_cliente, 
                   c.direccion as cliente_direccion, c.tipo as cliente_tipo,
                   a.nombre as almacen_nombre
            FROM comercial.pedido p
            LEFT JOIN cat.cliente c ON c.cliente_id = p.cliente_id
            LEFT JOIN cat.almacen a ON a.almacen_id = p.almacen_id
            WHERE p.pedido_id = ?
        ', [$id]);

        if (!$pedido) {
            abort(404);
        }

        $detalles = DB::select('
            SELECT pd.*, 
                   (pd.cantidad_t * pd.precio_unit_usd) as subtotal
            FROM comercial.pedidodetalle pd
            WHERE pd.pedido_id = ?
        ', [$id]);

        // Calcular totales
        $totalItems = count($detalles);
        $totalCantidad = array_sum(array_column($detalles, 'cantidad_t'));
        $totalMonto = array_sum(array_column($detalles, 'subtotal'));

        // Estados disponibles para el flujo
        $estadosDisponibles = $this->getEstadosSiguientes($pedido->estado);

        return view('comercial.pedidos.show', [
            'pedido' => $pedido,
            'detalles' => $detalles,
            'total_items' => $totalItems,
            'total_cantidad' => $totalCantidad,
            'total_monto' => $totalMonto,
            'estados_disponibles' => $estadosDisponibles
        ]);
    }

    /**
     * Cambiar estado del pedido
     */
    public function cambiarEstado(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => 'required|in:PENDIENTE,PREPARANDO,ENVIADO,ENTREGADO,CANCELADO'
        ]);

        DB::table('comercial.pedido')
            ->where('pedido_id', $id)
            ->update(['estado' => $validated['estado']]);

        return redirect()->route('comercial.pedidos.show', $id)
            ->with('success', 'Estado actualizado a ' . $validated['estado']);
    }

    /**
     * Obtener estados siguientes según el estado actual
     */
    private function getEstadosSiguientes(string $estadoActual): array
    {
        return match ($estadoActual) {
            'PENDIENTE', 'ABIERTO' => ['PREPARANDO', 'CANCELADO'],
            'PREPARANDO' => ['ENVIADO', 'CANCELADO'],
            'ENVIADO' => ['ENTREGADO'],
            'ENTREGADO' => [],
            'CANCELADO' => [],
            default => ['PREPARANDO', 'CANCELADO']
        };
    }
}
