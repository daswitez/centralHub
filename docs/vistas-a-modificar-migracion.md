# Vistas a Modificar para Migración a Microservicios

## Resumen

Este documento identifica todas las vistas que deben ser modificadas para prepararlas para consumir APIs de microservicios en lugar de acceder directamente a la base de datos.

**Objetivo**: Limpiar las vistas antes de implementar los consumos de API, identificando qué datos vienen del controlador y qué necesita cambiar.

---

## Estado de Limpieza

### ✅ Completado
- **Pedidos de Cliente** (`/comercial/pedidos`) - Todas las vistas limpiadas, solo GET
- **Solicitudes de Producción** (`/solicitudes`) - Todas las vistas limpiadas, solo GET
- **Lotes de Producción** (`/tx/planta/*`) - Todas las vistas limpiadas, solo GET
- **Transacciones de Almacén** (`/tx/almacen/*`) - Todas las vistas limpiadas, solo GET

### ⏳ Pendiente
- Ninguna (limpieza de vistas completada)

---

## Metodología

Para cada funcionalidad identificada en `analisis-migracion-microservicios.md`, se mapean:
1. **Vistas afectadas** - Archivos `.blade.php` que usan datos de BD
2. **Variables del controlador** - Datos que vienen del controlador
3. **Cambios necesarios** - Qué modificar en las vistas
4. **Rutas relacionadas** - Rutas que apuntan a estas vistas

---

## 1. Pedidos de Cliente (`/comercial/pedidos`)

### 🔴 Prioridad: ALTA - Reemplazar por Sistema Trazabilidad

### ✅ LIMPIEZA COMPLETADA (2024)

**Cambios realizados:**
- ✅ Removido botón "Nuevo Pedido" de `index.blade.php`
- ✅ Removido formulario completo de `create.blade.php` (reemplazado por mensaje informativo)
- ✅ Removido formulario de cambio de estado de `show.blade.php` (reemplazado por mensaje informativo)
- ✅ Removido link "Nuevo Pedido" del menú en `layouts/app.blade.php`
- ✅ Todas las vistas ahora son de solo lectura (GET únicamente)

**Estado actual:** Las vistas están listas para consumir datos de API. Solo falta implementar los consumos en los controladores.

#### Vistas Afectadas:

##### 1.1 `resources/views/comercial/pedidos/index.blade.php`
- **Ruta**: `GET /comercial/pedidos`
- **Controller**: `App\Http\Controllers\Comercial\PedidoController@index`
- **Variables del controlador**:
  - `$pedidos` - Array de objetos con: `pedido_id`, `codigo_pedido`, `fecha_pedido`, `estado`, `cliente_nombre`, `codigo_cliente`, `total_items`, `monto_total`
- **Datos que usa directamente**:
  - `$pedido->pedido_id` - Para links
  - `$pedido->codigo_pedido` - Mostrar código
  - `$pedido->cliente_nombre`, `$pedido->codigo_cliente` - Info cliente
  - `$pedido->fecha_pedido` - Fecha formateada
  - `$pedido->total_items` - Número de items
  - `$pedido->monto_total` - Monto total
  - `$pedido->estado` - Estado con badge
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Botón "Nuevo Pedido" removido del header
  - ✅ Solo muestra listado (GET)
  - ⚠️ Pendiente: Adaptar nombres de campos cuando se consuma API de Trazabilidad
  - ⚠️ Pendiente: Verificar formato de fechas y estructura de estados

##### 1.2 `resources/views/comercial/pedidos/create.blade.php`
- **Ruta**: `GET /comercial/pedidos/crear`
- **Controller**: `App\Http\Controllers\Comercial\PedidoController@create`
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Formulario completo removido
  - ✅ Reemplazado por mensaje informativo
  - ✅ Indica que la funcionalidad se gestiona desde microservicio de Trazabilidad
  - ✅ Botón para volver al listado
  - **Nota**: Esta vista puede eliminarse completamente o mantenerse como placeholder informativo

##### 1.3 `resources/views/comercial/pedidos/show.blade.php`
- **Ruta**: `GET /comercial/pedidos/{id}`
- **Controller**: `App\Http\Controllers\Comercial\PedidoController@show`
- **Variables del controlador**:
  - `$pedido` - Objeto con: `pedido_id`, `codigo_pedido`, `fecha_pedido`, `estado`, `cliente_nombre`, `codigo_cliente`, `cliente_tipo`, `cliente_direccion`, `almacen_nombre`
  - `$detalles` - Array de `pedidodetalle` con: `sku`, `cantidad_t`, `precio_unit_usd`, `subtotal`
  - `$total_items`, `$total_cantidad`, `$total_monto` - Totales calculados
  - `$estados_disponibles` - Array de estados siguientes (ya no se usa para formulario)
- **Datos que usa directamente**:
  - Información completa del pedido
  - Detalles de productos
  - Totales y KPIs
  - Timeline de estados (solo visualización)
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Formulario de cambio de estado removido
  - ✅ Reemplazado por mensaje informativo
  - ✅ Solo muestra información (GET)
  - ⚠️ Pendiente: Adaptar estructura de datos cuando se consuma API de Trazabilidad
  - ⚠️ Pendiente: Mostrar información de destinos si la API los incluye

#### Referencias en Layout:
- `resources/views/layouts/app.blade.php` (líneas 155-165)
  - Menú "Comercial" con links a pedidos
  - **Estado**: ✅ **LIMPIEZA COMPLETADA**
    - ✅ Link "Nuevo Pedido" removido del menú
    - ✅ Solo queda link a "Pedidos / Ventas" (index)

#### Referencias en Dashboards:
- `resources/views/panel/ventas.blade.php`
  - Usa datos de pedidos para KPIs y tabla
  - Variables: `$kpi_pedidos_hoy`, `$kpi_ingresos_mes`, `$kpi_pedidos_cerrados`, `$kpi_precio_promedio`, `$pedidos`, `$ventas_por_mes`
  - **Cambios**: El controlador `DashboardController@ventas` debe consumir API en lugar de consultar BD

---

## 2. Solicitudes de Producción (`/solicitudes`)

### 🔴 Prioridad: ALTA - Reemplazar parcialmente por OrgTrack

### ✅ LIMPIEZA COMPLETADA (2024)
### ✅ CONSUMO DE API IMPLEMENTADO (2024)

**Cambios realizados en vistas:**
- ✅ Removido botón "Nueva Solicitud" de `index.blade.php`
- ✅ Removido link "Crear Primera Solicitud" del empty state
- ✅ Removido formulario completo de `create.blade.php` (reemplazado por mensaje informativo)
- ✅ Removido modal y botón "Responder" de `mis_solicitudes.blade.php`
- ✅ Removido script JavaScript de respuesta
- ✅ Removido link "Nueva Solicitud" del menú en `layouts/app.blade.php`
- ✅ `show.blade.php` ya era solo visualización (sin cambios necesarios)

**Cambios realizados en controlador:**
- ✅ **Refactorizado `SolicitudProduccionController`** para consumir API de OrgTrack
- ✅ Reemplazadas todas las consultas SQL directas por llamadas a API
- ✅ Implementados métodos de transformación de datos (OrgTrack → formato legacy)
- ✅ Agregado manejo de errores con fallback a vistas vacías

**Endpoints de OrgTrack consumidos:**
- `GET /api/publico/envios` - Listar todos los envíos (usado en `index()`)
- `GET /api/publico/envio/{id}` - Obtener envío específico (usado en `show()`)
- `GET /api/publico/envios-productores` - Listar envíos entregados (usado en `misSolicitudes()`)
- `POST /api/publico/direccion` - Crear dirección (usado en `store()`)
- `POST /api/publico/envio` - Crear envío (usado en `store()`)

**Métodos de transformación implementados:**
- `mapearEstado()` - Mapea estados de OrgTrack (Pendiente, En curso, Finalizado) a formato legacy (PENDIENTE, ACEPTADA, COMPLETADA)
- `calcularPesoTotal()` - Suma pesos de todas las cargas y convierte kg → toneladas
- `extraerVariedad()` - Obtiene nombre del producto desde las cargas
- `extraerConductor()` - Obtiene nombre del transportista desde particiones
- `extraerTelefonoConductor()` - Obtiene teléfono del transportista
- `extraerEstadoAsignacion()` - Obtiene estado de la asignación
- `extraerFechaAsignacion()` - Obtiene fecha de asignación
- `construirParticiones()` - Construye estructura de particiones para crear envíos

**Estado actual:** 
- ✅ Las vistas están limpias (solo lectura)
- ✅ El controlador consume API de OrgTrack
- ✅ Los datos se transforman correctamente para las vistas existentes
- ⚠️ **Pendiente**: Pruebas de integración con OrgTrack real

**Nota importante:** 
- ⚠️ OrgTrack maneja envíos **Productor → Planta** (flujo de entrega)
- ⚠️ Este sistema originalmente manejaba Solicitudes **Planta → Productor** (flujo de solicitud)
- ✅ Se adaptó el controlador para usar OrgTrack como fuente de datos de envíos
- ⚠️ El método `responder()` ahora retorna error indicando que la funcionalidad está en OrgTrack

#### Vistas Afectadas:

##### 2.1 `resources/views/campo/solicitudes/index.blade.php`
- **Ruta**: `GET /solicitudes`
- **Controller**: `App\Http\Controllers\Campo\SolicitudProduccionController@index`
- **Estado**: ✅ **CONSUMO DE API COMPLETADO**
  - ✅ Consume `GET /api/publico/envios` de OrgTrack
  - ✅ Transforma datos al formato esperado por la vista
  - ✅ Manejo de errores con mensaje informativo

##### 2.2 `resources/views/campo/solicitudes/create.blade.php`
- **Ruta**: `GET /solicitudes/crear`
- **Controller**: `App\Http\Controllers\Campo\SolicitudProduccionController@create`
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Vista limpiada - solo muestra mensaje informativo
  - ⚠️ Método `store()` implementado pero requiere formulario actualizado

##### 2.3 `resources/views/campo/solicitudes/show.blade.php`
- **Ruta**: `GET /solicitudes/{id}`
- **Controller**: `App\Http\Controllers\Campo\SolicitudProduccionController@show`
- **Estado**: ✅ **CONSUMO DE API COMPLETADO**
  - ✅ Consume `GET /api/publico/envio/{id}` de OrgTrack
  - ✅ Transforma datos detallados al formato esperado
  - ✅ Incluye información de conductor, asignación y estado

##### 2.4 `resources/views/campo/solicitudes/mis_solicitudes.blade.php`
- **Ruta**: `GET /solicitudes/mis-solicitudes`
- **Controller**: `App\Http\Controllers\Campo\SolicitudProduccionController@misSolicitudes`
- **Estado**: ✅ **CONSUMO DE API COMPLETADO**
  - ✅ Consume `GET /api/publico/envios-productores` de OrgTrack
  - ✅ Ordena por estado (pendientes primero) y fecha necesaria
  - ✅ Modal y botón "Responder" ya removidos

#### Referencias en Layout:
- `resources/views/layouts/app.blade.php` (líneas 168-204)
  - **Estado**: ✅ **LIMPIEZA COMPLETADA**
    - ✅ Link "Nueva Solicitud" removido del menú
    - ✅ Solo quedan links a listados (index y mis-solicitudes)

---

## 3. Lotes de Producción (`/tx/planta/*`)

### 🟡 Prioridad: MEDIA - Evaluar migración a Sistema Trazabilidad

### ✅ LIMPIEZA COMPLETADA (2024)

**Cambios realizados:**
- ✅ Removido botón "Registrar Lote Planta" de `lotes_planta_index.blade.php`
- ✅ Removido botón "Registrar Lote Salida" de `lotes_salida_index.blade.php`
- ✅ Removido formulario completo de `lote_planta.blade.php` (reemplazado por mensaje informativo)
- ✅ Removido formulario completo de `lote_salida_envio.blade.php` (reemplazado por mensaje informativo)
- ✅ Removidos links a formularios de creación del menú en `layouts/app.blade.php`
- ✅ Solo quedan links a listados (index)

**Estado actual:** Las vistas están listas para consumir datos de API. Solo muestran información (GET) y no tienen formularios de creación.

**Nota importante:**
- ⚠️ Los formularios anteriores ejecutaban Stored Procedures (`planta.sp_registrar_lote_planta`, `planta.sp_registrar_lote_salida_y_envio`)
- ⚠️ El flujo en Trazabilidad es diferente: requiere asignar procesos, registrar transformaciones, gestionar almacén
- ⚠️ **Pendiente**: Adaptar estructura de datos cuando se consuma API de Trazabilidad (campos pueden ser diferentes)

#### Vistas Afectadas:

##### 3.1 `resources/views/tx/planta/lotes_planta_index.blade.php`
- **Ruta**: `GET /tx/planta/lotes-planta`
- **Controller**: `App\Http\Controllers\Planta\TransaccionPlantaController@indexLotesPlanta`
- **Variables del controlador**:
  - `$lotes` - Array con: `lote_planta_id`, `codigo_lote_planta`, `fecha_inicio`, `rendimiento_pct`, `planta_nombre`, `codigo_planta`, `total_lotes_campo`, `peso_total_entrada`
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Botón "Registrar Lote Planta" removido
  - ✅ Solo muestra listado (GET)
  - ⚠️ Pendiente: Adaptar a estructura de `production-batches` de Trazabilidad cuando se consuma API

##### 3.2 `resources/views/tx/planta/lotes_salida_index.blade.php`
- **Ruta**: `GET /tx/planta/lotes-salida`
- **Controller**: `App\Http\Controllers\Planta\TransaccionPlantaController@indexLotesSalida`
- **Variables del controlador**:
  - `$lotes` - Array con: `lote_salida_id`, `codigo_lote_salida`, `fecha_empaque`, `sku`, `peso_t`, `codigo_lote_planta`, `planta_nombre`
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Botón "Registrar Lote Salida" removido
  - ✅ Solo muestra listado (GET)
  - ⚠️ Pendiente: Adaptar campos cuando se consuma API de Trazabilidad

##### 3.3 `resources/views/tx/planta/lote_planta.blade.php`
- **Ruta**: `GET /tx/planta/lote-planta` (formulario)
- **Controller**: `App\Http\Controllers\Planta\TransaccionPlantaController@showLotePlantaForm`
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Formulario completo removido (ejecutaba SP `planta.sp_registrar_lote_planta`)
  - ✅ Reemplazado por mensaje informativo
  - ✅ Indica que la funcionalidad se gestiona desde microservicio de Trazabilidad
  - ✅ Nota sobre diferencia de flujos (Stored Procedure vs API con procesos y transformaciones)

##### 3.4 `resources/views/tx/planta/lote_salida_envio.blade.php`
- **Ruta**: `GET /tx/planta/lote-salida-envio` (formulario)
- **Controller**: `App\Http\Controllers\Planta\TransaccionPlantaController@showLoteSalidaEnvioForm`
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Formulario completo removido (ejecutaba SP `planta.sp_registrar_lote_salida_y_envio`)
  - ✅ Reemplazado por mensaje informativo
  - ✅ Indica que la funcionalidad se gestiona desde microservicio de Trazabilidad
  - ✅ Nota sobre diferencia de flujos (Stored Procedure vs API con gestión de almacén)

#### Referencias en Layout:
- `resources/views/layouts/app.blade.php` (líneas 127-147)
  - **Estado**: ✅ **LIMPIEZA COMPLETADA**
    - ✅ Links a formularios de creación removidos del menú
    - ✅ Solo quedan links a listados (index)

#### Referencias en Dashboards:
- `resources/views/panel/planta.blade.php`
  - Usa datos de lotes para mostrar batches
  - Variables: `$batches`, `$control_procesos`, `$kpi_rendimiento_promedio`, `$kpi_lotes_producidos`
  - **Estado**: ⚠️ **PENDIENTE** - El controlador debe consumir API de Trazabilidad

---

## 4. Transacciones de Almacén (`/tx/almacen/*`)

### 🟡 Prioridad: MEDIA - Evaluar migración parcial a Sistema Trazabilidad

### ✅ LIMPIEZA COMPLETADA (2024)

**Cambios realizados:**
- ✅ Removido formulario completo de `despachar_almacen.blade.php` (reemplazado por mensaje informativo)
- ✅ Removido formulario completo de `recepcionar_envio.blade.php` (reemplazado por mensaje informativo)
- ✅ Removido formulario completo de `despachar_cliente.blade.php` (reemplazado por mensaje informativo)
- ✅ Removidos scripts JavaScript de tablas dinámicas
- ✅ Removidos estilos CSS de timeline de trazabilidad

**Estado actual:** Las vistas están listas para consumir datos de API. Solo muestran información (GET) y no tienen formularios de transacciones.

**Nota importante:**
- ⚠️ Los formularios anteriores ejecutaban Stored Procedures (`almacen.sp_despachar_a_almacen`, `almacen.sp_recepcionar_envio`, `almacen.sp_despachar_a_cliente`)
- ⚠️ La lógica de negocio debe migrarse a microservicios (Almacén, Logística o Trazabilidad)
- ⚠️ **Pendiente**: Implementar consumos de API en los controladores

#### Vistas Afectadas:

##### 4.1 `resources/views/tx/almacen/despachar_almacen.blade.php`
- **Ruta**: `GET /tx/almacen/despachar-al-almacen`
- **Controller**: `App\Http\Controllers\Almacen\TransaccionAlmacenController@showDespacharAlmacenForm`
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Formulario completo removido (ejecutaba SP `almacen.sp_despachar_a_almacen`)
  - ✅ Reemplazado por mensaje informativo
  - ✅ Indica que la funcionalidad se gestionará desde microservicios
  - ⚠️ Pendiente: Implementar consumo de API cuando esté disponible

##### 4.2 `resources/views/tx/almacen/recepcionar_envio.blade.php`
- **Ruta**: `GET /tx/almacen/recepcionar-envio`
- **Controller**: `App\Http\Controllers\Almacen\TransaccionAlmacenController@showRecepcionarEnvioForm`
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Formulario completo removido (ejecutaba SP `almacen.sp_recepcionar_envio`)
  - ✅ Removido timeline interactivo de trazabilidad con JavaScript
  - ✅ Removidos estilos CSS del timeline
  - ✅ Reemplazado por mensaje informativo
  - ⚠️ Pendiente: Implementar consumo de API cuando esté disponible

##### 4.3 `resources/views/tx/almacen/despachar_cliente.blade.php`
- **Ruta**: `GET /tx/almacen/despachar-al-cliente`
- **Controller**: `App\Http\Controllers\Almacen\TransaccionAlmacenController@showDespacharClienteForm`
- **Estado**: ✅ **LIMPIEZA COMPLETADA**
  - ✅ Formulario completo removido (ejecutaba SP `almacen.sp_despachar_a_cliente`)
  - ✅ Reemplazado por mensaje informativo
  - ✅ Indica que la funcionalidad se gestionará desde microservicios
  - ⚠️ Pendiente: Implementar consumo de API cuando esté disponible

#### Referencias en Dashboards:
- `resources/views/almacen/dashboard.blade.php`
  - Dashboard de almacén con KPIs
  - Variables: `$kpi_total_stock`, `$kpi_total_almacenes`, `$kpi_total_skus`, `$kpi_recepciones_hoy`, `$stock_por_almacen`, `$stock_detalle`, `$ultimas_recepciones`, `$ultimos_movimientos`
  - **Cambios**: El controlador debe consumir APIs

---

## 5. Dashboards (`/panel/*`)

### 🟢 Prioridad: BAJA - Mantener pero consumir APIs

#### Vistas Afectadas:

##### 5.1 `resources/views/panel/home.blade.php`
- **Ruta**: `GET /panel` o `GET /panel/home`
- **Controller**: `App\Http\Controllers\Panel\DashboardController@home`
- **Variables del controlador**: (Muchas - ver controlador)
  - KPIs: `$kpi_stock_t`, `$kpi_envios_hoy`, `$kpi_envios_en_ruta`, `$kpi_ordenes_pendientes`, `$kpi_lotes_mes`, `$kpi_toneladas_empacadas`, `$kpi_productores`, `$kpi_pedidos_mes`, `$kpi_vehiculos_disponibles`, `$kpi_rendimiento`
  - Tablas: `$ventas_por_cliente`, `$ventas_mes_totales`, `$ultimos_envios`, `$ultimas_ordenes`, `$ultimos_lotes`, `$variedades`, `$plantas`
  - Gráficos: `$envios_por_estado`, `$produccion_mensual`
- **Cambios necesarios**:
  - ⚠️ **NO MODIFICAR VISTA** - Solo el controlador cambiará
  - ⚠️ El controlador debe consumir múltiples APIs para obtener estos datos
  - ⚠️ Mantener estructura de la vista intacta
  - ⚠️ Verificar que los nombres de variables se mantengan iguales

##### 5.2 `resources/views/panel/ventas.blade.php`
- **Ruta**: `GET /panel/ventas`
- **Controller**: `App\Http\Controllers\Panel\DashboardController@ventas`
- **Variables del controlador**:
  - KPIs: `$kpi_pedidos_hoy`, `$kpi_ingresos_mes`, `$kpi_pedidos_cerrados`, `$kpi_precio_promedio`
  - Datos: `$pedidos`, `$ventas_por_mes`
- **Cambios necesarios**:
  - ⚠️ **NO MODIFICAR VISTA** - Solo el controlador
  - ⚠️ El controlador debe consumir API de Trazabilidad para pedidos
  - ⚠️ Mantener estructura y nombres de variables

##### 5.3 `resources/views/panel/logistica.blade.php`
- **Ruta**: `GET /panel/logistica`
- **Controller**: `App\Http\Controllers\Panel\DashboardController@logistica`
- **Variables del controlador**:
  - `$envios`, `$kpi_envios_en_ruta`, `$kpi_envios_completados`, `$kpi_tonelaje_transito`
- **Cambios necesarios**:
  - ⚠️ **NO MODIFICAR VISTA** - Solo el controlador
  - ⚠️ Puede consumir datos de OrgTrack para envíos Productor → Planta

##### 5.4 `resources/views/panel/planta.blade.php`
- **Ruta**: `GET /panel/planta`
- **Controller**: `App\Http\Controllers\Panel\DashboardController@planta`
- **Variables del controlador**:
  - `$batches`, `$control_procesos`, `$kpi_rendimiento_promedio`, `$kpi_lotes_producidos`
- **Cambios necesarios**:
  - ⚠️ **NO MODIFICAR VISTA** - Solo el controlador
  - ⚠️ Puede consumir API de Trazabilidad para batches

##### 5.5 `resources/views/panel/certificaciones.blade.php`
- **Ruta**: `GET /panel/certificaciones`
- **Controller**: `App\Http\Controllers\Panel\DashboardController@certificaciones`
- **Variables del controlador**:
  - `$certs`, `$kpi_certs_vigentes`, `$kpi_certs_por_vencer`
- **Cambios necesarios**:
  - ⚠️ **NO MODIFICAR VISTA** - Solo el controlador
  - ⚠️ Mantener sistema actual (no se migra a microservicio aún)

##### 5.6 `resources/views/almacen/dashboard.blade.php`
- **Ruta**: `GET /panel/almacen`
- **Controller**: `App\Http\Controllers\Almacen\AlmacenDashboardController@index`
- **Variables del controlador**:
  - `$kpi_total_stock`, `$kpi_total_almacenes`, `$kpi_total_skus`, `$kpi_recepciones_hoy`
  - `$stock_por_almacen`, `$stock_detalle`, `$ultimas_recepciones`, `$ultimos_movimientos`
- **Cambios necesarios**:
  - ⚠️ **NO MODIFICAR VISTA** - Solo el controlador
  - ⚠️ Puede consumir API de Trazabilidad para almacenamiento básico
  - ⚠️ Mantener estructura

---

## 6. Resumen de Cambios por Prioridad

### 🔴 Prioridad ALTA - Modificar Vistas

#### Pedidos de Cliente:
1. ✅ `resources/views/comercial/pedidos/index.blade.php` - **Mínimos cambios** (adaptar campos)
2. 🔴 `resources/views/comercial/pedidos/create.blade.php` - **REDISEÑO COMPLETO** (estructura diferente en API)
3. ⚠️ `resources/views/comercial/pedidos/show.blade.php` - **Cambios moderados** (adaptar estructura de datos)

#### Solicitudes de Producción:
4. ⚠️ `resources/views/campo/solicitudes/*.blade.php` (4 vistas) - **Evaluar si mantener o eliminar** (flujo inverso a OrgTrack)

### 🟡 Prioridad MEDIA - Evaluar y Limpiar

#### Lotes de Producción:
5. ⚠️ `resources/views/tx/planta/lotes_planta_index.blade.php` - **Adaptar campos** (si se migra)
6. ⚠️ `resources/views/tx/planta/lotes_salida_index.blade.php` - **Adaptar campos** (si se migra)
7. 🔴 `resources/views/tx/planta/lote_planta.blade.php` - **REQUIERE ANÁLISIS** (SP vs API)
8. 🔴 `resources/views/tx/planta/lote_salida_envio.blade.php` - **REQUIERE ANÁLISIS** (SP vs API)

#### Transacciones de Almacén:
9. ⚠️ `resources/views/tx/almacen/despachar_almacen.blade.php` - **Limpiar, datos de APIs**
10. ⚠️ `resources/views/tx/almacen/recepcionar_envio.blade.php` - **Limpiar, datos de APIs**
11. ⚠️ `resources/views/tx/almacen/despachar_cliente.blade.php` - **Limpiar, datos de APIs**

### 🟢 Prioridad BAJA - No Modificar Vistas (Solo Controladores)

#### Dashboards:
12. ✅ `resources/views/panel/home.blade.php` - **NO TOCAR** (solo controlador)
13. ✅ `resources/views/panel/ventas.blade.php` - **NO TOCAR** (solo controlador)
14. ✅ `resources/views/panel/logistica.blade.php` - **NO TOCAR** (solo controlador)
15. ✅ `resources/views/panel/planta.blade.php` - **NO TOCAR** (solo controlador)
16. ✅ `resources/views/panel/certificaciones.blade.php` - **NO TOCAR** (solo controlador)
17. ✅ `resources/views/almacen/dashboard.blade.php` - **NO TOCAR** (solo controlador)

---

## 7. Plan de Limpieza de Vistas

### Fase 1: Limpieza Inmediata (Sin cambios funcionales)

**Objetivo**: Identificar y documentar todas las referencias a datos de BD en vistas

1. ✅ **Completado**: Documentación de vistas afectadas (este documento)

### Fase 2: Preparación de Vistas (Antes de consumir APIs)

**Objetivo**: Limpiar vistas para que solo muestren datos, sin lógica de BD

#### 2.1 Vistas de Pedidos
- [ ] Revisar `comercial/pedidos/index.blade.php` - Verificar que solo use variables del controlador
- [ ] **REDISEÑAR** `comercial/pedidos/create.blade.php` - Adaptar a estructura de API Trazabilidad
- [ ] Revisar `comercial/pedidos/show.blade.php` - Verificar estructura de datos

#### 2.2 Vistas de Solicitudes
- [ ] Evaluar si mantener o eliminar vistas de solicitudes
- [ ] Si se mantienen: Limpiar referencias a BD directa
- [ ] Si se eliminan: Documentar redirección a OrgTrack

#### 2.3 Vistas de Lotes de Planta
- [ ] Revisar vistas de listado - Solo limpiar
- [ ] **ANALIZAR** vistas de formularios - Evaluar migración a Trazabilidad

#### 2.4 Vistas de Almacén
- [ ] Limpiar todas las vistas de transacciones
- [ ] Verificar que solo usen variables del controlador

### Fase 3: Modificaciones Post-API (Después de implementar consumos)

**Objetivo**: Adaptar vistas a estructura de datos de APIs

- [ ] Adaptar nombres de campos según respuesta de APIs
- [ ] Adaptar estructuras de datos (arrays, objetos anidados)
- [ ] Manejar errores de API en vistas
- [ ] Agregar loading states mientras se cargan datos de API

---

## 8. Checklist de Limpieza por Vista

### Para cada vista, verificar:

- [ ] ✅ No hay consultas SQL directas en la vista
- [ ] ✅ No hay llamadas a `DB::` en la vista
- [ ] ✅ No hay llamadas a modelos Eloquent en la vista (ej: `Model::all()`)
- [ ] ✅ Todas las variables vienen del controlador
- [ ] ✅ Los nombres de variables son consistentes
- [ ] ✅ Los formatos de datos son correctos (fechas, números, etc.)
- [ ] ✅ Los links y rutas usan `route()` en lugar de URLs hardcodeadas
- [ ] ✅ Los formularios apuntan a rutas correctas
- [ ] ✅ Los campos de formulario tienen nombres correctos
- [ ] ✅ Los selects usan datos del controlador, no consultas directas

---

## 9. Notas Importantes

### Vistas que NO deben modificarse aún:
- Dashboards (`panel/*.blade.php`) - Solo cambiarán los controladores
- Vistas de catálogos (`cat/*.blade.php`) - No se migran en esta fase
- Vistas de campo (`campo/*.blade.php` excepto solicitudes) - No se migran en esta fase
- Vistas de logística (`logistica/*.blade.php`) - No se migran en esta fase
- Vistas de certificaciones (`certificacion/*.blade.php`) - No se migran en esta fase
- Vistas de reportes (`reportes/*.blade.php`) - No se migran en esta fase

### Vistas que requieren rediseño completo:
1. 🔴 `comercial/pedidos/create.blade.php` - Estructura completamente diferente en API Trazabilidad

### Vistas que requieren análisis antes de modificar:
1. 🔴 `tx/planta/lote_planta.blade.php` - Usa Stored Procedure, flujo diferente en Trazabilidad
2. 🔴 `tx/planta/lote_salida_envio.blade.php` - Usa Stored Procedure, flujo diferente en Trazabilidad
3. ⚠️ `campo/solicitudes/*.blade.php` - Evaluar si mantener o reemplazar por OrgTrack

---

## 10. Archivos de Referencia

- **Análisis de migración**: `docs/analisis-migracion-microservicios.md`
- **Documentación de rutas**: `docs/routes-web-documentation.md`
- **API Trazabilidad**: `docs/trazabilidad.md`
- **API OrgTrack**: `docs/orgtrack.md`

---

**Fecha de Documentación**: 2024  
**Versión**: 1.0  
**Estado**: Listo para limpieza de vistas

