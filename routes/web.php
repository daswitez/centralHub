<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cat\DepartamentoController;
use App\Http\Controllers\Cat\MunicipioController;
use App\Http\Controllers\Cat\VariedadPapaController;
use App\Http\Controllers\Cat\PlantaController;
use App\Http\Controllers\Cat\ClienteController;
use App\Http\Controllers\Cat\TransportistaController;
use App\Http\Controllers\Cat\AlmacenController;
use App\Http\Controllers\Campo\ProductorController;
use App\Http\Controllers\Campo\LoteCampoController;
use App\Http\Controllers\Campo\SensorLecturaController;
use App\Http\Controllers\Planta\TransaccionPlantaController;
use App\Http\Controllers\Almacen\TransaccionAlmacenController;
use App\Http\Controllers\Panel\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Comercial\PedidoController;
use App\Http\Controllers\Campo\SolicitudProduccionController;
use App\Http\Controllers\TrazabilidadController;

// Rutas de autenticación (públicas)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Ruta raíz - redirige a dashboard si autenticado, sino a login
Route::get('/', function () {
    return redirect()->route('panel.home');
})->middleware('auth');

// Todas las rutas protegidas con autenticación
Route::middleware(['auth'])->group(function () {
    
    // Rutas CRUD para catálogos base (prefijo /cat)
    Route::prefix('cat')->name('cat.')->group(function () {
        Route::resource('departamentos', DepartamentoController::class)->except(['show']);
        Route::resource('municipios', MunicipioController::class)->except(['show']);
        Route::resource('variedades', VariedadPapaController::class)->except(['show']);
        Route::resource('plantas', PlantaController::class)->except(['show']);
        Route::resource('clientes', ClienteController::class)->except(['show']);
        Route::resource('transportistas', TransportistaController::class)->except(['show']);
        Route::resource('almacenes', AlmacenController::class)->except(['show']);
    });

    // Rutas CRUD para campo (prefijo /campo)
    Route::prefix('campo')->name('campo.')->group(function () {
        Route::resource('productores', ProductorController::class)->except(['show']);
        Route::resource('lotes', LoteCampoController::class)->except(['show']);
        Route::resource('lecturas', SensorLecturaController::class)->except(['show']);
    });
    
    // Rutas para comercial/ventas
    Route::prefix('comercial')->name('comercial.')->group(function () {
        Route::resource('pedidos', PedidoController::class)->only(['index', 'create', 'store']);
    });

    // Paneles (dashboards)
    Route::prefix('panel')->name('panel.')->group(function () {
        Route::get('/', [DashboardController::class, 'home'])->name('home');
        Route::get('/ventas', [DashboardController::class, 'ventas'])->name('ventas');
        Route::get('/logistica', [DashboardController::class, 'logistica'])->name('logistica');
        Route::get('/planta', [DashboardController::class, 'planta'])->name('planta');
        Route::get('/certificaciones', [DashboardController::class, 'certificaciones'])->name('certificaciones');
    });

    // API endpoints for transactions
    Route::prefix('api')->group(function () {
        Route::get('/envios/buscar/{codigo}', [App\Http\Controllers\Almacen\TransaccionAlmacenController::class, 'buscarEnvio']);
    });

    // Transacciones de negocio (SPs en PostgreSQL)
    Route::prefix('tx')->name('tx.')->group(function () {
        // Planta
        Route::prefix('planta')->name('planta.')->group(function () {
            Route::get('lotes-planta', [TransaccionPlantaController::class, 'indexLotesPlanta'])
                ->name('lotes-planta.index');
            Route::get('lotes-salida', [TransaccionPlantaController::class, 'indexLotesSalida'])
                ->name('lotes-salida.index');
                
            Route::get('lote-planta', [TransaccionPlantaController::class, 'showLotePlantaForm'])
                ->name('lote-planta.form');
            Route::post('lote-planta', [TransaccionPlantaController::class, 'registrarLotePlanta'])
                ->name('lote-planta.store');

            Route::get('lote-salida-envio', [TransaccionPlantaController::class, 'showLoteSalidaEnvioForm'])
                ->name('lote-salida-envio.form');
            Route::post('lote-salida-envio', [TransaccionPlantaController::class, 'registrarLoteSalidaEnvio'])
                ->name('lote-salida-envio.store');
        });

        // Almacén
        Route::prefix('almacen')->name('almacen.')->group(function () {
            Route::get('despachar-al-almacen', [TransaccionAlmacenController::class, 'showDespacharAlmacenForm'])
                ->name('despachar-al-almacen.form');
            Route::post('despachar-al-almacen', [TransaccionAlmacenController::class, 'despacharAlmacen'])
                ->name('despachar-al-almacen.store');

            Route::get('recepcionar-envio', [TransaccionAlmacenController::class, 'showRecepcionarEnvioForm'])
                ->name('recepcionar-envio.form');
            Route::post('recepcionar-envio', [TransaccionAlmacenController::class, 'recepcionarEnvio'])
                ->name('recepcionar-envio.store');

            Route::get('despachar-al-cliente', [TransaccionAlmacenController::class, 'showDespacharClienteForm'])
                ->name('despachar-al-cliente.form');
            Route::post('despachar-al-cliente', [TransaccionAlmacenController::class, 'despacharCliente'])
                ->name('despachar-al-cliente.store');
        });
    });

    // Solicitudes de Producción
    Route::prefix('solicitudes')->name('solicitudes.')->group(function () {
        Route::get('/', [SolicitudProduccionController::class, 'index'])
            ->name('index');
        Route::get('/crear', [SolicitudProduccionController::class, 'create'])
            ->name('create');
        Route::post('/', [SolicitudProduccionController::class, 'store'])
            ->name('store');
        Route::get('/mis-solicitudes', [SolicitudProduccionController::class, 'misSolicitudes'])
            ->name('mis-solicitudes');
        Route::get('/{id}', [SolicitudProduccionController::class, 'show'])
            ->name('show');
        Route::post('/{id}/responder', [SolicitudProduccionController::class, 'responder'])
            ->name('responder');
    });

    // Trazabilidad
    Route::prefix('trazabilidad')->name('trazabilidad.')->group(function () {
        Route::get('/', [TrazabilidadController::class, 'index'])
            ->name('index');
    });

    // API para trazabilidad
    Route::prefix('api')->group(function () {
        Route::get('/trazabilidad/{tipo}/{codigo}', [TrazabilidadController::class, 'getDatosCompletos']);
    });
    
    // Exportar PDF de trazabilidad
    Route::get('/trazabilidad/pdf/{tipo}/{codigo}', [TrazabilidadController::class, 'exportPdf'])
        ->name('trazabilidad.pdf');
    
    // Almacenes (show adicional)
    Route::get('/cat/almacenes/{id}', [AlmacenController::class, 'show'])->name('cat.almacenes.show');
    
    // Vehículos
    Route::prefix('vehiculos')->name('vehiculos.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Cat\VehiculoController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Cat\VehiculoController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Cat\VehiculoController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Cat\VehiculoController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [\App\Http\Controllers\Cat\VehiculoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Cat\VehiculoController::class, 'update'])->name('update');
        Route::post('/{id}/asignar-conductor', [\App\Http\Controllers\Cat\VehiculoController::class, 'asignarConductor'])->name('asignar-conductor');
    });
    
    // Órdenes de Envío (Planta → Almacén)
    Route::prefix('ordenes-envio')->name('ordenes-envio.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Logistica\OrdenEnvioController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Logistica\OrdenEnvioController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Logistica\OrdenEnvioController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Logistica\OrdenEnvioController::class, 'show'])->name('show');
        Route::get('/{id}/pdf', [\App\Http\Controllers\Logistica\OrdenEnvioController::class, 'exportPdf'])->name('pdf');
        Route::post('/{id}/asignar-conductor', [\App\Http\Controllers\Logistica\OrdenEnvioController::class, 'asignarConductor'])->name('asignar-conductor');
        Route::post('/{id}/cambiar-estado', [\App\Http\Controllers\Logistica\OrdenEnvioController::class, 'cambiarEstado'])->name('cambiar-estado');
    });
    
    // Dashboard alias
    Route::get('/dashboard', function() {
        return redirect()->route('panel.home');
    })->name('dashboard');
});