# Documentación de Rutas Web - API Gateway Central Hub

## Resumen General

Este documento describe todas las rutas web del sistema API Gateway que actualmente utiliza su propia base de datos PostgreSQL. El sistema está diseñado para ser un frontend que consume microservicios, pero actualmente accede directamente a la base de datos.

**Estado actual**: Sistema monolítico con acceso directo a BD  
**Objetivo**: Migrar a arquitectura de microservicios donde este sistema solo consuma APIs

---

## Estructura de Rutas

### 1. Autenticación (Públicas)

#### `GET /login`
- **Controller**: `App\Http\Controllers\Auth\LoginController@showLoginForm`
- **Descripción**: Muestra formulario de login
- **Retorna**: Vista `auth.login`
- **Datos que usa**: Ninguno (solo vista)

#### `POST /login`
- **Controller**: `App\Http\Controllers\Auth\LoginController@login`
- **Descripción**: Procesa autenticación de usuario
- **Recibe**:
  - `email` (required, email)
  - `password` (required)
  - `remember` (opcional, boolean)
- **Retorna**: Redirect a `/dashboard` si éxito, back con errores si falla
- **Datos que usa**: Tabla `users` (Laravel Auth)

#### `POST /logout`
- **Controller**: `App\Http\Controllers\Auth\LoginController@logout`
- **Descripción**: Cierra sesión del usuario
- **Retorna**: Redirect a `/login`

---

### 2. Catálogos Base (`/cat`)

Todas las rutas de catálogos usan el patrón RESTful estándar (excepto `show`).

#### Departamentos
- **Rutas**: `GET /cat/departamentos`, `POST /cat/departamentos`, `PUT /cat/departamentos/{id}`, `DELETE /cat/departamentos/{id}`
- **Controller**: `App\Http\Controllers\Cat\DepartamentoController`
- **Modelo/BD**: `cat.departamento`
- **Operaciones**: CRUD básico de departamentos geográficos
- **Datos que recibe**:
  - `nombre` (string, required)
  - `codigo_departamento` (string, opcional)

#### Municipios
- **Rutas**: `GET /cat/municipios`, `POST /cat/municipios`, `PUT /cat/municipios/{id}`, `DELETE /cat/municipios/{id}`
- **Controller**: `App\Http\Controllers\Cat\MunicipioController`
- **Modelo/BD**: `cat.municipio`
- **Operaciones**: CRUD básico de municipios
- **Datos que recibe**:
  - `nombre` (string, required)
  - `departamento_id` (integer, required)
  - `codigo_municipio` (string, opcional)

#### Variedades de Papa
- **Rutas**: `GET /cat/variedades`, `POST /cat/variedades`, `PUT /cat/variedades/{id}`, `DELETE /cat/variedades/{id}`
- **Controller**: `App\Http\Controllers\Cat\VariedadPapaController`
- **Modelo/BD**: `cat.variedadpapa`
- **Operaciones**: CRUD de variedades de papa
- **Datos que recibe**:
  - `nombre_comercial` (string, required)
  - `codigo_variedad` (string, opcional)
  - `aptitud` (string, opcional)

#### Plantas
- **Rutas**: `GET /cat/plantas`, `POST /cat/plantas`, `PUT /cat/plantas/{id}`, `DELETE /cat/plantas/{id}`
- **Controller**: `App\Http\Controllers\Cat\PlantaController`
- **Modelo/BD**: `cat.planta`
- **Operaciones**: CRUD de plantas procesadoras
- **Datos que recibe**:
  - `nombre` (string, required)
  - `codigo_planta` (string, required)
  - `direccion` (string, opcional)
  - `municipio_id` (integer, opcional)

#### Clientes
- **Rutas**: `GET /cat/clientes`, `POST /cat/clientes`, `PUT /cat/clientes/{id}`, `DELETE /cat/clientes/{id}`
- **Controller**: `App\Http\Controllers\Cat\ClienteController`
- **Modelo/BD**: `cat.cliente`
- **Operaciones**: CRUD de clientes con estadísticas de pedidos
- **Datos que recibe**:
  - `codigo_cliente` (string, required, max:40)
  - `nombre` (string, required, max:160)
  - `tipo` (string, required, max:60) - Valores: MAYORISTA, RETAIL, PROCESADOR
  - `municipio_id` (integer, opcional)
  - `direccion` (string, opcional, max:200)
  - `lat`, `lon` (numeric, opcional)
- **Datos adicionales calculados**: Total pedidos, pedidos completados, monto total

#### Transportistas
- **Rutas**: `GET /cat/transportistas`, `POST /cat/transportistas`, `PUT /cat/transportistas/{id}`, `DELETE /cat/transportistas/{id}`
- **Controller**: `App\Http\Controllers\Cat\TransportistaController`
- **Modelo/BD**: `cat.transportista`
- **Operaciones**: CRUD de transportistas/conductores
- **Datos que recibe**:
  - `nombre` (string, required)
  - `codigo_transp` (string, opcional)
  - `telefono` (string, opcional)
  - `estado` (string) - Valores: DISPONIBLE, OCUPADO

#### Almacenes
- **Rutas**: `GET /cat/almacenes`, `POST /cat/almacenes`, `PUT /cat/almacenes/{id}`, `DELETE /cat/almacenes/{id}`, `GET /cat/almacenes/{id}` (show adicional)
- **Controller**: `App\Http\Controllers\Cat\AlmacenController`
- **Modelo/BD**: `cat.almacen`
- **Operaciones**: CRUD de almacenes
- **Datos que recibe**:
  - `nombre` (string, required)
  - `codigo_almacen` (string, required)
  - `direccion` (string, opcional)
  - `municipio_id` (integer, opcional)

#### Vehículos
- **Rutas**: 
  - `GET /vehiculos` - Lista vehículos
  - `GET /vehiculos/crear` - Formulario crear
  - `POST /vehiculos` - Guardar vehículo
  - `GET /vehiculos/{id}` - Ver detalle
  - `GET /vehiculos/{id}/editar` - Formulario editar
  - `PUT /vehiculos/{id}` - Actualizar
  - `POST /vehiculos/{id}/asignar-conductor` - Asignar conductor
- **Controller**: `App\Http\Controllers\Cat\VehiculoController`
- **Modelo/BD**: `cat.vehiculo`
- **Operaciones**: CRUD completo de vehículos con asignación de conductores
- **Datos que recibe**:
  - `codigo_vehiculo` (string, required, unique)
  - `placa` (string, required, unique)
  - `marca`, `modelo` (string, required)
  - `anio` (integer, opcional)
  - `capacidad_t` (numeric, required)
  - `tipo` (string, required) - Valores: CAMION, FURGON, REFRIGERADO, CISTERNA
  - `estado` (string) - Valores: DISPONIBLE, EN_USO, MANTENIMIENTO, FUERA_SERVICIO
  - `kilometraje` (integer, opcional)

---

### 3. Gestión de Campo (`/campo`)

#### Productores
- **Rutas**: `GET /campo/productores`, `POST /campo/productores`, `PUT /campo/productores/{id}`, `DELETE /campo/productores/{id}`
- **Controller**: `App\Http\Controllers\Campo\ProductorController`
- **Modelo/BD**: `campo.productor`
- **Operaciones**: CRUD de productores agrícolas
- **Datos que recibe**:
  - `nombre` (string, required)
  - `codigo_productor` (string, opcional)
  - `municipio_id` (integer, opcional)
  - `telefono` (string, opcional)

#### Lotes de Campo
- **Rutas**: `GET /campo/lotes`, `POST /campo/lotes`, `PUT /campo/lotes/{id}`, `DELETE /campo/lotes/{id}`
- **Controller**: `App\Http\Controllers\Campo\LoteCampoController`
- **Modelo/BD**: `campo.lotecampo`
- **Operaciones**: CRUD de lotes de producción agrícola
- **Datos que recibe**:
  - `codigo_lote_campo` (string, required)
  - `productor_id` (integer, required)
  - `variedad_id` (integer, required)
  - `superficie_ha` (numeric, opcional)
  - `fecha_siembra`, `fecha_cosecha` (date, opcional)
  - `peso_t` (numeric, opcional)

#### Lecturas de Sensores
- **Rutas**: `GET /campo/lecturas`, `POST /campo/lecturas`, `PUT /campo/lecturas/{id}`, `DELETE /campo/lecturas/{id}`
- **Controller**: `App\Http\Controllers\Campo\SensorLecturaController`
- **Modelo/BD**: `campo.sensorlectura`
- **Operaciones**: CRUD de lecturas de sensores IoT en campo
- **Datos que recibe**: Variables ambientales (temperatura, humedad, etc.)

#### Solicitudes de Producción
- **Rutas**:
  - `GET /solicitudes` - Lista todas las solicitudes
  - `GET /solicitudes/crear` - Formulario crear
  - `POST /solicitudes` - Guardar solicitud
  - `GET /solicitudes/mis-solicitudes` - Solicitudes del productor
  - `GET /solicitudes/{id}` - Ver detalle
  - `POST /solicitudes/{id}/responder` - Aceptar/Rechazar solicitud
- **Controller**: `App\Http\Controllers\Campo\SolicitudProduccionController`
- **Modelo/BD**: `campo.solicitud_produccion`, `campo.asignacion_conductor`
- **Operaciones**: Gestión de solicitudes de producción de plantas a productores
- **Datos que recibe**:
  - `planta_id` (integer, required)
  - `productor_id` (integer, required)
  - `variedad_id` (integer, required)
  - `cantidad_solicitada_t` (numeric, required)
  - `fecha_necesaria` (date, required, after:today)
  - `observaciones` (string, opcional)
- **Lógica especial**: Al aceptar, asigna automáticamente un conductor disponible

---

### 4. Comercial/Ventas (`/comercial`)

#### Pedidos
- **Rutas**:
  - `GET /comercial/pedidos` - Lista pedidos
  - `GET /comercial/pedidos/crear` - Formulario crear
  - `POST /comercial/pedidos` - Guardar pedido
  - `GET /comercial/pedidos/{id}` - Ver detalle
  - `PUT /comercial/pedidos/{id}/estado` - Cambiar estado
- **Controller**: `App\Http\Controllers\Comercial\PedidoController`
- **Modelo/BD**: `comercial.pedido`, `comercial.pedidodetalle`
- **Operaciones**: Gestión de pedidos de clientes
- **Datos que recibe**:
  - `cliente_id` (integer, required)
  - `fecha_pedido` (date, required)
  - `observaciones` (string, opcional)
  - `detalles` (array, required, min:1) - Array de items:
    - `sku` (string, required)
    - `cantidad_t` (numeric, required, min:0.01)
    - `precio_unit_usd` (numeric, required, min:0.01)
- **Estados**: PENDIENTE, PREPARANDO, ENVIADO, ENTREGADO, CANCELADO
- **Lógica**: Genera código automático `PED-YYYY-XXX`

---

### 5. Paneles/Dashboards (`/panel`)

#### Dashboard Principal
- **Ruta**: `GET /panel` o `GET /panel/home`
- **Controller**: `App\Http\Controllers\Panel\DashboardController@home`
- **Descripción**: Dashboard ejecutivo con KPIs y resúmenes generales
- **Datos que consulta**:
  - Inventario total en almacenes (`almacen.inventario`)
  - Envíos del día (`logistica.envio`)
  - Envíos en ruta activos
  - Órdenes de envío pendientes (`logistica.orden_envio`)
  - Lotes procesados este mes (`planta.loteplanta`)
  - Toneladas empacadas este mes (`planta.lotesalida`)
  - Productores registrados (`campo.productor`)
  - Pedidos del mes (`comercial.pedido`)
  - Vehículos disponibles (`cat.vehiculo`)
  - Rendimiento promedio de plantas
  - Ventas por cliente (Top 5)
  - Últimos envíos, órdenes, lotes de salida
  - Distribución por variedad
  - Estado de plantas
  - Envíos por estado (gráfico)
  - Producción mensual (últimos 6 meses)

#### Dashboard de Ventas
- **Ruta**: `GET /panel/ventas`
- **Controller**: `App\Http\Controllers\Panel\DashboardController@ventas`
- **Descripción**: Panel comercial con métricas de ventas
- **Datos que consulta**:
  - Pedidos del día (`comercial.pedido`)
  - Ingresos del mes (`comercial.pedidodetalle`)
  - Pedidos cerrados del mes
  - Precio promedio por tonelada
  - Últimos pedidos con detalles
  - Ventas por canal (últimos 7 meses) - Mayorista, Retail, Procesador

#### Dashboard de Logística
- **Ruta**: `GET /panel/logistica`
- **Controller**: `App\Http\Controllers\Panel\DashboardController@logistica`
- **Descripción**: Panel de seguimiento logístico
- **Datos que consulta**:
  - Envíos con información completa (`logistica.envio`, `logistica.enviodetalle`)
  - Envíos en ruta
  - Envíos completados del mes
  - Tonelaje en tránsito

#### Dashboard de Planta
- **Ruta**: `GET /panel/planta`
- **Controller**: `App\Http\Controllers\Panel\DashboardController@planta`
- **Descripción**: Panel de procesos de planta
- **Datos que consulta**:
  - Batches con trazabilidad (`planta.lotesalida`, `planta.loteplanta`)
  - Control de procesos recientes (`planta.controlproceso`)
  - Rendimiento promedio del mes
  - Lotes producidos del mes

#### Dashboard de Certificaciones
- **Ruta**: `GET /panel/certificaciones`
- **Controller**: `App\Http\Controllers\Panel\DashboardController@certificaciones`
- **Descripción**: Panel de certificaciones
- **Datos que consulta**:
  - Certificados con lotes asociados (`certificacion.certificado`)
  - Certificados vigentes
  - Certificados por vencer (próximos 30 días)

#### Dashboard de Almacén
- **Ruta**: `GET /panel/almacen`
- **Controller**: `App\Http\Controllers\Almacen\AlmacenDashboardController@index`
- **Descripción**: Panel de inventario de almacenes
- **Datos que consulta**:
  - Stock total (`almacen.inventario`)
  - Total de almacenes con stock
  - Total de SKUs
  - Recepciones del día (`almacen.recepcion`)
  - Stock por almacén
  - Stock detallado por SKU (Top 20)
  - Últimas recepciones
  - Últimos movimientos (`almacen.movimiento`)

---

### 6. Transacciones de Negocio (`/tx`)

#### Transacciones de Planta

##### Listado de Lotes de Planta
- **Ruta**: `GET /tx/planta/lotes-planta`
- **Controller**: `App\Http\Controllers\Planta\TransaccionPlantaController@indexLotesPlanta`
- **Descripción**: Lista lotes de planta con información de entradas de campo
- **Datos que consulta**: `planta.loteplanta`, `planta.loteplanta_entradacampo`

##### Listado de Lotes de Salida
- **Ruta**: `GET /tx/planta/lotes-salida`
- **Controller**: `App\Http\Controllers\Planta\TransaccionPlantaController@indexLotesSalida`
- **Descripción**: Lista lotes de salida de planta
- **Datos que consulta**: `planta.lotesalida`, `planta.loteplanta`

##### Registrar Lote de Planta
- **Ruta**: `GET /tx/planta/lote-planta` (form), `POST /tx/planta/lote-planta` (store)
- **Controller**: `App\Http\Controllers\Planta\TransaccionPlantaController@showLotePlantaForm`, `registrarLotePlanta`
- **Descripción**: Registra entrada de lotes de campo a planta
- **Recibe**:
  - `codigo_lote_planta` (string, required)
  - `planta_id` (integer, required)
  - `fecha_inicio` (date, required)
  - `entradas` (array, required, min:1):
    - `lote_campo_id` (integer, required)
    - `peso_entrada_t` (numeric, required, min:0.001)
- **Ejecuta**: `planta.sp_registrar_lote_planta` (Stored Procedure)

##### Registrar Lote de Salida y Envío
- **Ruta**: `GET /tx/planta/lote-salida-envio` (form), `POST /tx/planta/lote-salida-envio` (store)
- **Controller**: `App\Http\Controllers\Planta\TransaccionPlantaController@showLoteSalidaEnvioForm`, `registrarLoteSalidaEnvio`
- **Descripción**: Registra lote de salida y opcionalmente crea envío
- **Recibe**:
  - `codigo_lote_salida` (string, required)
  - `lote_planta_id` (integer, required)
  - `sku` (string, required)
  - `peso_t` (numeric, required)
  - `fecha_empaque` (date, required)
  - `crear_envio` (boolean, opcional)
  - Si `crear_envio`:
    - `codigo_envio` (string, required)
    - `ruta_id` (integer, opcional)
    - `transportista_id` (integer, opcional)
    - `fecha_salida` (date, opcional)
- **Ejecuta**: `planta.sp_registrar_lote_salida_y_envio` (Stored Procedure)

#### Transacciones de Almacén

##### Despachar a Almacén
- **Ruta**: `GET /tx/almacen/despachar-al-almacen` (form), `POST /tx/almacen/despachar-al-almacen` (store)
- **Controller**: `App\Http\Controllers\Almacen\TransaccionAlmacenController@showDespacharAlmacenForm`, `despacharAlmacen`
- **Descripción**: Crea envío desde planta/almacen origen a almacén destino
- **Recibe**:
  - `codigo_envio` (string, required)
  - `transportista_id` (integer, required)
  - `almacen_destino_id` (integer, required)
  - `fecha_salida` (date, required)
  - `detalle` (array, required, min:1):
    - `codigo_lote_salida` (string, required)
    - `cantidad_t` (numeric, required, min:0.001)
- **Ejecuta**: `almacen.sp_despachar_a_almacen` (Stored Procedure)

##### Recepcionar Envío
- **Ruta**: `GET /tx/almacen/recepcionar-envio` (form), `POST /tx/almacen/recepcionar-envio` (store)
- **Controller**: `App\Http\Controllers\Almacen\TransaccionAlmacenController@showRecepcionarEnvioForm`, `recepcionarEnvio`
- **Descripción**: Recepciona envío en almacén y actualiza inventario
- **Recibe**:
  - `codigo_envio` (string, required)
  - `almacen_id` (integer, required)
  - `observacion` (string, opcional)
- **Ejecuta**: `almacen.sp_recepcionar_envio` (Stored Procedure)

##### Despachar a Cliente
- **Ruta**: `GET /tx/almacen/despachar-al-cliente` (form), `POST /tx/almacen/despachar-al-cliente` (store)
- **Controller**: `App\Http\Controllers\Almacen\TransaccionAlmacenController@showDespacharClienteForm`, `despacharCliente`
- **Descripción**: Crea envío desde almacén a cliente y descuenta stock
- **Recibe**:
  - `codigo_envio` (string, required)
  - `almacen_origen_id` (integer, required)
  - `cliente_id` (integer, required)
  - `transportista_id` (integer, required)
  - `fecha_salida` (date, required)
  - `detalle` (array, required, min:1):
    - `codigo_lote_salida` (string, required)
    - `cantidad_t` (numeric, required, min:0.001)
- **Ejecuta**: `almacen.sp_despachar_a_cliente` (Stored Procedure)

##### Buscar Envío (API)
- **Ruta**: `GET /api/envios/buscar/{codigo}`
- **Controller**: `App\Http\Controllers\Almacen\TransaccionAlmacenController@buscarEnvio`
- **Descripción**: API para buscar información completa de un envío
- **Retorna**: JSON con datos del envío, transportista, ruta, detalles de lotes

---

### 7. Logística (`/ordenes-envio`)

#### Órdenes de Envío (Planta → Almacén)
- **Rutas**:
  - `GET /ordenes-envio` - Lista órdenes (con filtro por estado)
  - `GET /ordenes-envio/crear` - Formulario crear
  - `POST /ordenes-envio` - Guardar orden
  - `GET /ordenes-envio/{id}` - Ver detalle
  - `GET /ordenes-envio/{id}/pdf` - Exportar PDF (Guía de Remisión)
  - `POST /ordenes-envio/{id}/asignar-conductor` - Asignar conductor manualmente
  - `POST /ordenes-envio/{id}/cambiar-estado` - Cambiar estado
- **Controller**: `App\Http\Controllers\Logistica\OrdenEnvioController`
- **Modelo/BD**: `logistica.orden_envio`
- **Operaciones**: Gestión de órdenes de envío desde planta a almacén
- **Datos que recibe**:
  - `planta_origen_id` (integer, required)
  - `lote_salida_id` (integer, required)
  - `almacen_destino_id` (integer, required)
  - `cantidad_t` (numeric, required, min:0.1)
  - `fecha_programada` (date, required, after_or_equal:today)
  - `prioridad` (string, required) - Valores: URGENTE, NORMAL, BAJA
  - `observaciones` (string, opcional)
- **Estados**: PENDIENTE, CONDUCTOR_ASIGNADO, EN_CARGA, EN_RUTA, ENTREGADO, CANCELADO
- **Lógica especial**: 
  - Intenta asignación automática de conductor y vehículo al crear
  - Al cambiar a ENTREGADO, libera conductor y vehículo
  - Genera código automático `OE-YYYY-XXXX`

---

### 8. Trazabilidad (`/trazabilidad`)

#### Vista Principal
- **Ruta**: `GET /trazabilidad`
- **Controller**: `App\Http\Controllers\TrazabilidadController@index`
- **Descripción**: Vista para buscar trazabilidad por código
- **Datos que carga**: Listas de lotes campo, planta, salida, órdenes envío, envíos, recepciones, pedidos

#### API de Trazabilidad
- **Ruta**: `GET /api/trazabilidad/{tipo}/{codigo}`
- **Controller**: `App\Http\Controllers\TrazabilidadController@getDatosCompletos`
- **Descripción**: Obtiene trazabilidad completa desde cualquier punto
- **Parámetros**:
  - `tipo`: campo, planta, salida, orden_envio, envio, pedido
  - `codigo`: Código del elemento
- **Retorna**: JSON con etapas completas de trazabilidad (campo → planta → salida → envío → almacén)
- **Datos que consulta**: Múltiples tablas relacionadas según el tipo

#### Exportar PDF de Trazabilidad
- **Ruta**: `GET /trazabilidad/pdf/{tipo}/{codigo}`
- **Controller**: `App\Http\Controllers\TrazabilidadController@exportPdf`
- **Descripción**: Genera PDF con reporte de trazabilidad
- **Retorna**: PDF descargable

---

### 9. Certificaciones (`/certificaciones`)

- **Rutas**:
  - `GET /certificaciones` - Lista certificaciones (con filtros por ámbito y estado)
  - `GET /certificaciones/crear` - Formulario crear
  - `POST /certificaciones` - Guardar certificación
  - `GET /certificaciones/{id}` - Ver detalle con timeline
  - `GET /certificaciones/{id}/pdf` - Exportar PDF
  - `POST /certificaciones/{id}/evidencia` - Subir evidencia documental
  - `DELETE /certificaciones/{id}/evidencia/{evidenciaId}` - Eliminar evidencia
  - `GET /certificaciones/verificar-cadena/{lote_salida_id}` - Verificar si se puede emitir certificación general
- **Controller**: `App\Http\Controllers\Certificacion\CertificacionController`
- **Modelo/BD**: `certificacion.certificado`, `certificacion.certificadolotecampo`, `certificacion.certificadoloteplanta`, `certificacion.certificadolotesalida`, `certificacion.certificadoenvio`, `certificacion.certificadoevidencia`
- **Operaciones**: Gestión completa de certificaciones
- **Datos que recibe**:
  - `ambito` (string, required) - Valores: CAMPO, PLANTA, SALIDA, ENVIO, GENERAL
  - `area` (string, required) - Valores: HACCP, ISO, BPM, BPA, GLOBAL_GAP
  - `emisor` (string, required, max:160)
  - `vigente_desde` (date, required)
  - `vigente_hasta` (date, opcional, after:vigente_desde)
  - `lotes_campo`, `lotes_planta`, `lotes_salida`, `envios` (arrays opcionales)
- **Lógica especial**: 
  - Genera código automático `CERT-{AMBITO}-YYYY-XXXX`
  - Verifica cadena completa para certificación general
  - Maneja archivos de evidencia (PDF, imágenes, documentos)

---

### 10. Reportes Analíticos (`/reportes`)

#### Índice de Reportes
- **Ruta**: `GET /reportes`
- **Controller**: `App\Http\Controllers\Reportes\ReportesIndexController@index`
- **Descripción**: Página índice con lista de reportes disponibles

#### Reporte 1: Rentabilidad por Cliente
- **Rutas**:
  - `GET /reportes/rentabilidad-cliente` - Vista con filtros y gráficos
  - `GET /reportes/rentabilidad-cliente/pdf` - Exportar PDF
  - `GET /reportes/rentabilidad-cliente/csv` - Exportar Excel/CSV
- **Controller**: `App\Http\Controllers\Reportes\ReportRentabilidadController`
- **Descripción**: Análisis de rentabilidad por cliente
- **Filtros**:
  - `fecha_inicio`, `fecha_fin` (date)
  - `tipo_cliente` (string) - MAYORISTA, RETAIL, PROCESADOR, TODOS
  - `top_n` (integer) - Número de clientes a mostrar
- **Datos que consulta**: `comercial.pedido`, `comercial.pedidodetalle`, `cat.cliente`
- **Métricas**: Total pedidos, toneladas, ingresos, precio promedio, diferencia vs mercado

#### Reporte 2: Rendimiento de Plantas
- **Rutas**:
  - `GET /reportes/rendimiento-plantas` - Vista
  - `GET /reportes/rendimiento-plantas/pdf` - Exportar PDF
  - `GET /reportes/rendimiento-plantas/csv` - Exportar CSV
- **Controller**: `App\Http\Controllers\Reportes\ReportRendimientoController`
- **Descripción**: Análisis de rendimiento de plantas procesadoras
- **Datos que consulta**: `planta.loteplanta`, `planta.lotesalida`

#### Reporte 3: Análisis Logístico
- **Rutas**:
  - `GET /reportes/logistica` - Vista
  - `GET /reportes/logistica/pdf` - Exportar PDF
  - `GET /reportes/logistica/csv` - Exportar CSV
- **Controller**: `App\Http\Controllers\Reportes\ReportLogisticaController`
- **Descripción**: Análisis de eficiencia logística
- **Datos que consulta**: `logistica.envio`, `logistica.enviodetalle`, `logistica.orden_envio`

#### Reporte 4: Estado de Inventario
- **Rutas**:
  - `GET /reportes/inventario` - Vista
  - `GET /reportes/inventario/pdf` - Exportar PDF
  - `GET /reportes/inventario/csv` - Exportar CSV
- **Controller**: `App\Http\Controllers\Reportes\ReportInventarioController`
- **Descripción**: Estado actual de inventarios en almacenes
- **Datos que consulta**: `almacen.inventario`, `almacen.movimiento`

---

## Esquemas de Base de Datos Utilizados

### Esquemas PostgreSQL:
1. **`cat`** - Catálogos base (departamentos, municipios, variedades, plantas, clientes, transportistas, almacenes, vehículos)
2. **`campo`** - Gestión de campo (productores, lotes campo, lecturas sensores, solicitudes producción)
3. **`planta`** - Procesos de planta (lotes planta, lotes salida, control procesos)
4. **`comercial`** - Ventas (pedidos, pedidos detalle)
5. **`logistica`** - Logística (envíos, envíos detalle, órdenes envío, rutas)
6. **`almacen`** - Almacenes (inventario, recepciones, movimientos, zonas, ubicaciones)
7. **`certificacion`** - Certificaciones (certificados, asociaciones con lotes/envíos, evidencias)

---

## Stored Procedures Utilizados

1. **`planta.sp_registrar_lote_planta`** - Registra lote de planta con entradas de campo
2. **`planta.sp_registrar_lote_salida_y_envio`** - Registra lote de salida y opcionalmente crea envío
3. **`almacen.sp_despachar_a_almacen`** - Crea envío a almacén
4. **`almacen.sp_recepcionar_envio`** - Recepciona envío y actualiza inventario
5. **`almacen.sp_despachar_a_cliente`** - Crea envío a cliente y descuenta stock

---

## Notas para Migración a Microservicios

### Áreas que deben migrarse a microservicios:

1. **Microservicio de Catálogos**
   - Departamentos, Municipios, Variedades, Plantas, Clientes, Transportistas, Almacenes, Vehículos
   - Endpoints: CRUD completo para cada entidad

2. **Microservicio de Campo**
   - Productores, Lotes Campo, Lecturas Sensores, Solicitudes Producción
   - Endpoints: CRUD + lógica de asignación de conductores

3. **Microservicio de Planta**
   - Lotes Planta, Lotes Salida, Control Procesos
   - Endpoints: Transacciones + stored procedures

4. **Microservicio Comercial**
   - Pedidos, Pedidos Detalle
   - Endpoints: CRUD + cambio de estados

5. **Microservicio de Logística**
   - Envíos, Órdenes Envío, Rutas
   - Endpoints: CRUD + asignación conductores + cambio estados

6. **Microservicio de Almacén**
   - Inventario, Recepciones, Movimientos
   - Endpoints: Transacciones + stored procedures

7. **Microservicio de Certificaciones**
   - Certificados, Evidencias
   - Endpoints: CRUD + verificación de cadena

8. **Microservicio de Reportes**
   - Todos los reportes analíticos
   - Endpoints: Generación de reportes + exportación PDF/CSV

9. **Microservicio de Trazabilidad**
   - Consultas de trazabilidad completa
   - Endpoints: Búsqueda por tipo/código + exportación PDF

10. **Microservicio de Dashboards**
    - Agregación de datos para KPIs
    - Endpoints: Datos de dashboards por área

---

## Archivos MD Relacionados

Este documento forma parte de un conjunto de documentación para la migración a microservicios. Otros documentos relacionados:

- `orgtrack.md` - Documentación de organización y tracking
- `trazabilidad.md` - Documentación específica de trazabilidad
- (Otros MDs que el usuario esté utilizando)

---

## Fecha de Documentación

**Creado**: 2024  
**Última actualización**: 2024  
**Versión**: 1.0

