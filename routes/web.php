<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\DashboardController;

// Cat
use App\Http\Controllers\Cat\DepartamentoController;
use App\Http\Controllers\Cat\MunicipioController;
use App\Http\Controllers\Cat\VariedadPapaController;
use App\Http\Controllers\Cat\PlantaController;
use App\Http\Controllers\Cat\ClienteController;
use App\Http\Controllers\Cat\TransportistaController;
use App\Http\Controllers\Cat\AlmacenController;

// Campo
use App\Http\Controllers\Campo\ProductorController;
use App\Http\Controllers\Campo\LoteCampoController;
use App\Http\Controllers\Campo\SensorLecturaController;

// Planta
use App\Http\Controllers\Planta\LotePlantaController;
use App\Http\Controllers\Planta\LotePlantaEntradaCampoController;
use App\Http\Controllers\Planta\ControlProcesoController;
use App\Http\Controllers\Planta\LoteSalidaController;

// Logistica
use App\Http\Controllers\Logistica\RutaController;
use App\Http\Controllers\Logistica\RutaPuntoController;
use App\Http\Controllers\Logistica\EnvioController;
use App\Http\Controllers\Logistica\EnvioDetalleController;
use App\Http\Controllers\Logistica\EnvioDetalleAlmacenController;

// Comercial
use App\Http\Controllers\Comercial\PedidoController as ComercialPedidoController;
use App\Http\Controllers\Comercial\PedidoDetalleController as ComercialPedidoDetalleController;

// Certificacion
use App\Http\Controllers\Certificacion\CertificadoController;
use App\Http\Controllers\Certificacion\CertificadoLoteCampoController;
use App\Http\Controllers\Certificacion\CertificadoLotePlantaController;
use App\Http\Controllers\Certificacion\CertificadoLoteSalidaController;
use App\Http\Controllers\Certificacion\CertificadoEnvioController;
use App\Http\Controllers\Certificacion\CertificadoEvidenciaController;
use App\Http\Controllers\Certificacion\CertificadoCadenaController;

// Almacen
use App\Http\Controllers\Almacen\PedidoController as AlmacenPedidoController;
use App\Http\Controllers\Almacen\PedidoDetalleController as AlmacenPedidoDetalleController;
use App\Http\Controllers\Almacen\RecepcionController;
use App\Http\Controllers\Almacen\InventarioController;
use App\Http\Controllers\Almacen\MovimientoController;

// Tx
use App\Http\Controllers\Planta\TransaccionPlantaController;
use App\Http\Controllers\Almacen\TransaccionAlmacenController;

Route::get('/', function () {
    return redirect()->route('panel.home');
});

// Rutas CRUD para catálogos base (prefijo /cat)
Route::prefix('cat')->name('cat.')->group(function () {
    Route::resource('departamentos', DepartamentoController::class);
    Route::resource('municipios', MunicipioController::class);
    Route::resource('variedades', VariedadPapaController::class);
    Route::resource('plantas', PlantaController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('transportistas', TransportistaController::class);
    Route::resource('almacenes', AlmacenController::class);
});

// Rutas CRUD para campo (prefijo /campo)
Route::prefix('campo')->name('campo.')->group(function () {
    Route::resource('productores', ProductorController::class);
    Route::resource('lotes', LoteCampoController::class);
    Route::resource('lecturas', SensorLecturaController::class);
});

// Rutas CRUD para planta (prefijo /planta)
Route::prefix('planta')->name('planta.')->group(function () {
    Route::resource('loteplantas', LotePlantaController::class);
    Route::resource('entradas-campo', LotePlantaEntradaCampoController::class);
    Route::resource('controles', ControlProcesoController::class);
    Route::resource('lotesalidas', LoteSalidaController::class);
});

// Rutas CRUD para logistica (prefijo /logistica)
Route::prefix('logistica')->name('logistica.')->group(function () {
    Route::resource('rutas', RutaController::class);
    Route::resource('puntos', RutaPuntoController::class);
    Route::resource('envios', EnvioController::class);
    Route::resource('enviodetalles', EnvioDetalleController::class);
    Route::resource('enviodetallesalmacen', EnvioDetalleAlmacenController::class);
});

// Rutas CRUD para comercial (prefijo /comercial)
Route::prefix('comercial')->name('comercial.')->group(function () {
    Route::resource('pedidos', ComercialPedidoController::class);
    Route::resource('detalles', ComercialPedidoDetalleController::class);
});

// Rutas CRUD para certificacion (prefijo /certificacion)
Route::prefix('certificacion')->name('certificacion.')->group(function () {
    Route::resource('certificados', CertificadoController::class);
    Route::resource('lotescampo', CertificadoLoteCampoController::class);
    Route::resource('lotesplanta', CertificadoLotePlantaController::class);
    Route::resource('lotessalida', CertificadoLoteSalidaController::class);
    Route::resource('envios', CertificadoEnvioController::class);
    Route::resource('evidencias', CertificadoEvidenciaController::class);
    Route::resource('cadenas', CertificadoCadenaController::class);
});

// Rutas CRUD para almacen (prefijo /almacen)
Route::prefix('almacen')->name('almacen.')->group(function () {
    Route::resource('pedidos', AlmacenPedidoController::class);
    Route::resource('detalles', AlmacenPedidoDetalleController::class);
    Route::resource('recepciones', RecepcionController::class);
    Route::resource('inventarios', InventarioController::class);
    Route::resource('movimientos', MovimientoController::class);
});

// Paneles (dashboards)
Route::prefix('panel')->name('panel.')->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/ventas', [DashboardController::class, 'ventas'])->name('ventas');
    Route::get('/logistica', [DashboardController::class, 'logistica'])->name('logistica');
    Route::get('/planta', [DashboardController::class, 'planta'])->name('planta');
    Route::get('/certificaciones', [DashboardController::class, 'certificaciones'])->name('certificaciones');
});

// Transacciones de negocio (SPs en PostgreSQL)
Route::prefix('tx')->name('tx.')->group(function () {
    // Planta
    Route::prefix('planta')->name('planta.')->group(function () {
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