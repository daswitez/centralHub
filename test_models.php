<?php

use Illuminate\Support\Facades\Schema;
use App\Models\Cat\Departamento;
use App\Models\Cat\Municipio;
use App\Models\Cat\VariedadPapa;
use App\Models\Cat\Planta;
use App\Models\Cat\Cliente;
use App\Models\Cat\Transportista;
use App\Models\Cat\Almacen;
use App\Models\Campo\Productor;
use App\Models\Campo\LoteCampo;
use App\Models\Campo\SensorLectura;
use App\Models\Planta\LotePlanta;
use App\Models\Planta\LotePlantaEntradaCampo;
use App\Models\Planta\ControlProceso;
use App\Models\Planta\LoteSalida;
use App\Models\Logistica\Ruta;
use App\Models\Logistica\RutaPunto;
use App\Models\Logistica\Envio;
use App\Models\Logistica\EnvioDetalle;
use App\Models\Logistica\EnvioDetalleAlmacen;
use App\Models\Comercial\Pedido as ComercialPedido;
use App\Models\Comercial\PedidoDetalle as ComercialPedidoDetalle;
use App\Models\Certificacion\Certificado;
use App\Models\Certificacion\CertificadoLoteCampo;
use App\Models\Certificacion\CertificadoLotePlanta;
use App\Models\Certificacion\CertificadoLoteSalida;
use App\Models\Certificacion\CertificadoEnvio;
use App\Models\Certificacion\CertificadoEvidencia;
use App\Models\Certificacion\CertificadoCadena;
use App\Models\Almacen\Pedido as AlmacenPedido;
use App\Models\Almacen\PedidoDetalle as AlmacenPedidoDetalle;
use App\Models\Almacen\Recepcion;
use App\Models\Almacen\Inventario;
use App\Models\Almacen\Movimiento;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$models = [
    'Cat\Departamento' => Departamento::class,
    'Cat\Municipio' => Municipio::class,
    'Cat\VariedadPapa' => VariedadPapa::class,
    'Cat\Planta' => Planta::class,
    'Cat\Cliente' => Cliente::class,
    'Cat\Transportista' => Transportista::class,
    'Cat\Almacen' => Almacen::class,
    'Campo\Productor' => Productor::class,
    'Campo\LoteCampo' => LoteCampo::class,
    'Campo\SensorLectura' => SensorLectura::class,
    'Planta\LotePlanta' => LotePlanta::class,
    'Planta\LotePlantaEntradaCampo' => LotePlantaEntradaCampo::class,
    'Planta\ControlProceso' => ControlProceso::class,
    'Planta\LoteSalida' => LoteSalida::class,
    'Logistica\Ruta' => Ruta::class,
    'Logistica\RutaPunto' => RutaPunto::class,
    'Logistica\Envio' => Envio::class,
    'Logistica\EnvioDetalle' => EnvioDetalle::class,
    'Logistica\EnvioDetalleAlmacen' => EnvioDetalleAlmacen::class,
    'Comercial\Pedido' => ComercialPedido::class,
    'Comercial\PedidoDetalle' => ComercialPedidoDetalle::class,
    'Certificacion\Certificado' => Certificado::class,
    'Certificacion\CertificadoLoteCampo' => CertificadoLoteCampo::class,
    'Certificacion\CertificadoLotePlanta' => CertificadoLotePlanta::class,
    'Certificacion\CertificadoLoteSalida' => CertificadoLoteSalida::class,
    'Certificacion\CertificadoEnvio' => CertificadoEnvio::class,
    'Certificacion\CertificadoEvidencia' => CertificadoEvidencia::class,
    'Certificacion\CertificadoCadena' => CertificadoCadena::class,
    'Almacen\Pedido' => AlmacenPedido::class,
    'Almacen\PedidoDetalle' => AlmacenPedidoDetalle::class,
    'Almacen\Recepcion' => Recepcion::class,
    'Almacen\Inventario' => Inventario::class,
    'Almacen\Movimiento' => Movimiento::class,
];

foreach ($models as $name => $class) {
    try {
        $count = $class::count();
        echo "[OK] $name: $count records found.\n";
    } catch (\Exception $e) {
        echo "[ERROR] $name: " . $e->getMessage() . "\n";
    }
}
