# Análisis de Migración a Microservicios - Comparación de Sistemas

## Resumen Ejecutivo

Este documento analiza qué funcionalidades del sistema actual (`routes-web-documentation.md`) pueden ser reemplazadas consumiendo APIs de los sistemas **Trazabilidad** (`trazabilidad.md`) y **OrgTrack** (`orgtrack.md`).

**Objetivo**: Identificar oportunidades de migración para reducir dependencia de BD propia y consumir microservicios existentes.

---

## Metodología de Análisis

Se compararon las funcionalidades de los tres sistemas identificando:
1. **Funcionalidades equivalentes** - Pueden reemplazarse directamente
2. **Funcionalidades parciales** - Requieren adaptación o extensión
3. **Funcionalidades únicas** - No tienen equivalente y deben mantenerse
4. **Gaps** - Funcionalidades que faltan en los microservicios

---

## 1. Sistema Trazabilidad - Análisis de Reemplazo

### ✅ Funcionalidades que PUEDEN reemplazarse completamente

#### 1.1 Pedidos de Cliente (`/comercial/pedidos`)

**Sistema Actual:**
- `GET /comercial/pedidos` - Lista pedidos
- `POST /comercial/pedidos` - Crear pedido
- `GET /comercial/pedidos/{id}` - Ver detalle
- `PUT /comercial/pedidos/{id}/estado` - Cambiar estado

**Sistema Trazabilidad:**
- `POST /api/customer-orders` - Crear pedido (público, sin auth)
- `GET /api/customer-orders/by-user` - Obtener pedidos por usuario
- `PUT /api/customer-orders/{id}/public` - Actualizar pedido (público)
- `GET /api/customer-orders` - Listar pedidos (con auth)
- `GET /api/customer-orders/{id}` - Ver detalle (con auth)
- `PUT /api/customer-orders/{id}` - Actualizar (con auth)
- `DELETE /api/customer-orders/{id}` - Eliminar
- `POST /api/customer-orders/{id}/cancel` - Cancelar

**Conclusión**: ✅ **REEMPLAZABLE**
- El sistema Trazabilidad tiene funcionalidad más completa
- Incluye aprobación de pedidos (`/api/order-approval/*`)
- Soporta múltiples destinos y productos
- **Acción**: Reemplazar completamente `PedidoController` por consumo de API

**Consideraciones**:
- El sistema actual usa `comercial.pedido` y `comercial.pedidodetalle`
- El sistema Trazabilidad usa estructura diferente pero equivalente
- Requiere mapeo de datos entre estructuras

---

#### 1.2 Lotes de Producción (`/tx/planta/lotes-planta`, `/tx/planta/lotes-salida`)

**Sistema Actual:**
- `GET /tx/planta/lotes-planta` - Lista lotes de planta
- `GET /tx/planta/lotes-salida` - Lista lotes de salida
- `POST /tx/planta/lote-planta` - Registrar lote de planta (SP)
- `POST /tx/planta/lote-salida-envio` - Registrar lote salida (SP)

**Sistema Trazabilidad:**
- `GET /api/production-batches` - Listar lotes de producción
- `POST /api/production-batches` - Crear lote
- `GET /api/production-batches/{id}` - Ver detalle
- `PUT /api/production-batches/{id}` - Actualizar
- `GET /api/batches/pending-certification` - Lotes pendientes certificación
- `POST /api/batches/{batchId}/assign-process` - Asignar proceso
- `GET /api/batches/{batchId}/process-machines` - Máquinas del proceso
- `POST /api/batches/{batchId}/finalize-certification` - Finalizar certificación

**Conclusión**: ⚠️ **PARCIALMENTE REEMPLAZABLE**
- El sistema Trazabilidad tiene concepto de "lotes de producción" similar
- **DIFERENCIA CLAVE**: El sistema actual usa Stored Procedures (`planta.sp_registrar_lote_planta`)
- El sistema Trazabilidad tiene flujo más complejo con procesos y máquinas
- **Acción**: Evaluar si el flujo de Trazabilidad cubre las necesidades o requiere adaptación

**Consideraciones**:
- El sistema actual está más enfocado en "lotes de campo → planta → salida"
- El sistema Trazabilidad está más enfocado en "pedidos → lotes → procesos → certificación"
- Pueden ser complementarios o requerir unificación

---

#### 1.3 Almacenamiento (`/tx/almacen/*`, `/panel/almacen`)

**Sistema Actual:**
- `GET /tx/almacen/despachar-al-almacen` - Despachar a almacén
- `POST /tx/almacen/recepcionar-envio` - Recepcionar envío
- `POST /tx/almacen/despachar-al-cliente` - Despachar a cliente
- `GET /panel/almacen` - Dashboard de almacén

**Sistema Trazabilidad:**
- `GET /api/storages` - Listar almacenamientos
- `POST /api/storages` - Crear almacenamiento
- `GET /api/storages/batch/{batchId}` - Almacenamiento por lote

**Conclusión**: ⚠️ **PARCIALMENTE REEMPLAZABLE**
- El sistema Trazabilidad tiene almacenamiento básico
- **FALTA**: Funcionalidad de recepción, despacho, inventario detallado
- El sistema actual tiene stored procedures complejos (`almacen.sp_*`)
- **Acción**: El sistema Trazabilidad puede cubrir almacenamiento básico, pero faltan transacciones complejas

**Consideraciones**:
- El sistema actual maneja inventario, recepciones, movimientos
- El sistema Trazabilidad solo maneja almacenamiento de lotes
- Pueden complementarse: Trazabilidad para almacenamiento de lotes, sistema actual para inventario/transacciones

---

#### 1.4 Movimientos de Materiales

**Sistema Actual:**
- No tiene endpoint específico, pero maneja movimientos en almacén

**Sistema Trazabilidad:**
- `GET /api/material-movement-logs` - Listar movimientos
- `POST /api/material-movement-logs` - Crear movimiento
- `GET /api/material-movement-logs/material/{materialId}` - Movimientos por material

**Conclusión**: ✅ **AGREGAR FUNCIONALIDAD**
- El sistema actual no tiene esta funcionalidad explícita
- El sistema Trazabilidad la tiene completa
- **Acción**: Consumir API de Trazabilidad para agregar esta funcionalidad al sistema

---

### ⚠️ Funcionalidades que requieren ADAPTACIÓN

#### 1.5 Certificaciones (`/certificaciones`)

**Sistema Actual:**
- CRUD completo de certificaciones
- Asociación con lotes campo, planta, salida, envíos
- Verificación de cadena completa
- Evidencias documentales

**Sistema Trazabilidad:**
- Certificación implícita en lotes (`/api/batches/{batchId}/finalize-certification`)
- No tiene CRUD de certificaciones independiente
- No maneja evidencias documentales

**Conclusión**: ⚠️ **NO REEMPLAZABLE DIRECTAMENTE**
- El sistema actual tiene certificaciones más completas
- El sistema Trazabilidad solo certifica lotes de producción
- **Acción**: Mantener sistema actual, pero integrar certificación de lotes desde Trazabilidad

---

### ❌ Funcionalidades que NO tienen equivalente

#### 1.6 Catálogos Base (`/cat/*`)
- Departamentos, Municipios, Variedades, Plantas, Clientes, Transportistas, Almacenes, Vehículos
- **Conclusión**: El sistema Trazabilidad tiene algunos catálogos (clientes, productos) pero no todos
- **Acción**: Mantener o crear microservicio de catálogos

#### 1.7 Gestión de Campo (`/campo/*`)
- Productores, Lotes Campo, Lecturas Sensores, Solicitudes Producción
- **Conclusión**: No existe en Trazabilidad
- **Acción**: Mantener o crear microservicio de campo

#### 1.8 Transacciones de Planta (Stored Procedures)
- `planta.sp_registrar_lote_planta`
- `planta.sp_registrar_lote_salida_y_envio`
- **Conclusión**: Lógica de negocio específica, no existe en Trazabilidad
- **Acción**: Mantener o migrar a microservicio de planta

#### 1.9 Logística (`/ordenes-envio`)
- Órdenes de envío Planta → Almacén
- **Conclusión**: No existe en Trazabilidad
- **Acción**: Mantener o crear microservicio de logística

#### 1.10 Trazabilidad (`/trazabilidad`)
- Búsqueda de trazabilidad completa
- **Conclusión**: El sistema actual tiene su propia implementación
- **Acción**: Evaluar si el sistema Trazabilidad puede proporcionar esta funcionalidad

#### 1.11 Reportes Analíticos (`/reportes/*`)
- Rentabilidad, Rendimiento, Logística, Inventario
- **Conclusión**: No existe en Trazabilidad
- **Acción**: Mantener o crear microservicio de reportes

#### 1.12 Dashboards (`/panel/*`)
- Dashboards ejecutivos por área
- **Conclusión**: No existe en Trazabilidad
- **Acción**: Mantener, pero consumir datos de APIs de microservicios

---

## 2. Sistema OrgTrack - Análisis de Reemplazo

### ✅ Funcionalidades que PUEDEN reemplazarse completamente

#### 2.1 Envíos de Productor a Planta

**Sistema Actual:**
- `GET /solicitudes` - Solicitudes de producción
- `POST /solicitudes` - Crear solicitud
- `POST /solicitudes/{id}/responder` - Aceptar/Rechazar
- `GET /ordenes-envio` - Órdenes de envío

**Sistema OrgTrack:**
- `POST /api/publico/envio` - Crear envío de productor
- `GET /api/publico/envios` - Listar envíos
- `GET /api/publico/envio/{id}` - Ver detalle envío
- `GET /api/publico/envios-productores` - Envíos entregados
- `GET /api/publico/documento/{id_envio}` - Documento de envío

**Conclusión**: ✅ **REEMPLAZABLE PARCIALMENTE**
- OrgTrack maneja envíos de **Productor → Planta**
- El sistema actual maneja **Solicitudes de Producción** (Planta → Productor) y **Órdenes de Envío** (Planta → Almacén)
- **DIFERENCIA**: Flujos diferentes pero complementarios
- **Acción**: 
  - Usar OrgTrack para envíos Productor → Planta
  - Mantener sistema actual para Solicitudes y Órdenes Planta → Almacén

**Consideraciones**:
- OrgTrack tiene flujo completo con asignación de transportista, vehículo, tracking
- Incluye checklists, firmas digitales, documentos
- El sistema actual tiene flujo más simple

---

#### 2.2 Gestión de Direcciones

**Sistema Actual:**
- No tiene gestión explícita de direcciones
- Las direcciones están en catálogos (productores, plantas, almacenes, clientes)

**Sistema OrgTrack:**
- `POST /api/publico/direccion` - Crear dirección de productor
- Maneja direcciones con coordenadas y rutas GeoJSON

**Conclusión**: ✅ **AGREGAR FUNCIONALIDAD**
- El sistema actual no tiene esta funcionalidad
- OrgTrack la tiene completa
- **Acción**: Consumir API de OrgTrack para agregar gestión de direcciones

---

#### 2.3 Documentos de Envío

**Sistema Actual:**
- `GET /ordenes-envio/{id}/pdf` - Exportar PDF de orden de envío

**Sistema OrgTrack:**
- `GET /api/publico/documento/{id_envio}` - Documento completo con firmas, checklists, etc.

**Conclusión**: ⚠️ **PARCIALMENTE REEMPLAZABLE**
- OrgTrack tiene documentos más completos (con firmas, checklists)
- El sistema actual tiene documentos más simples
- **Acción**: Evaluar si usar documentos de OrgTrack o mantener ambos

---

### ❌ Funcionalidades que NO tienen equivalente

#### 2.4 Órdenes de Envío Planta → Almacén
- **Conclusión**: OrgTrack solo maneja Productor → Planta
- **Acción**: Mantener sistema actual

#### 2.5 Resto de funcionalidades
- Todas las demás funcionalidades del sistema actual no tienen equivalente en OrgTrack
- **Acción**: Mantener sistema actual

---

## 3. Resumen de Recomendaciones

### 🔴 Prioridad ALTA - Reemplazar Inmediatamente

1. **Pedidos de Cliente** (`/comercial/pedidos`)
   - **Reemplazar por**: Sistema Trazabilidad `/api/customer-orders/*`
   - **Razón**: Funcionalidad más completa, incluye aprobación
   - **Esfuerzo**: Medio (requiere mapeo de datos)

2. **Movimientos de Materiales**
   - **Agregar desde**: Sistema Trazabilidad `/api/material-movement-logs/*`
   - **Razón**: Funcionalidad que no existe actualmente
   - **Esfuerzo**: Bajo

3. **Envíos Productor → Planta**
   - **Reemplazar por**: Sistema OrgTrack `/api/publico/envio/*`
   - **Razón**: Flujo completo con tracking y documentos
   - **Esfuerzo**: Medio-Alto (requiere adaptación de flujo)

---

### 🟡 Prioridad MEDIA - Evaluar y Adaptar

4. **Lotes de Producción** (`/tx/planta/*`)
   - **Evaluar**: Sistema Trazabilidad `/api/production-batches/*`
   - **Razón**: Conceptos similares pero flujos diferentes
   - **Esfuerzo**: Alto (requiere análisis de negocio)

5. **Almacenamiento** (`/tx/almacen/*`)
   - **Evaluar**: Sistema Trazabilidad `/api/storages/*`
   - **Razón**: Funcionalidad básica existe, pero faltan transacciones complejas
   - **Esfuerzo**: Alto (requiere extensión del microservicio)

6. **Gestión de Direcciones**
   - **Agregar desde**: Sistema OrgTrack `/api/publico/direccion`
   - **Razón**: Funcionalidad útil que no existe
   - **Esfuerzo**: Bajo-Medio

---

### 🟢 Prioridad BAJA - Mantener Sistema Actual

7. **Catálogos Base** (`/cat/*`)
   - **Acción**: Crear microservicio de catálogos o mantener actual
   - **Razón**: No existe en otros sistemas

8. **Gestión de Campo** (`/campo/*`)
   - **Acción**: Crear microservicio de campo o mantener actual
   - **Razón**: No existe en otros sistemas

9. **Transacciones de Planta (SPs)**
   - **Acción**: Migrar a microservicio de planta
   - **Razón**: Lógica de negocio específica

10. **Logística** (`/ordenes-envio`)
    - **Acción**: Crear microservicio de logística
    - **Razón**: No existe en otros sistemas

11. **Certificaciones** (`/certificaciones`)
    - **Acción**: Mantener actual, integrar certificación de lotes desde Trazabilidad
    - **Razón**: Más completo que Trazabilidad

12. **Trazabilidad** (`/trazabilidad`)
    - **Acción**: Evaluar si Trazabilidad puede proporcionar esta funcionalidad
    - **Razón**: Sistema Trazabilidad puede tener funcionalidad equivalente

13. **Reportes Analíticos** (`/reportes/*`)
    - **Acción**: Crear microservicio de reportes
    - **Razón**: No existe en otros sistemas

14. **Dashboards** (`/panel/*`)
    - **Acción**: Mantener, consumir datos de APIs
    - **Razón**: Agregación de datos de múltiples fuentes

---

## 4. Plan de Migración Sugerido

### Fase 1: Migraciones Inmediatas (1-2 meses)
1. ✅ Migrar Pedidos de Cliente a Sistema Trazabilidad
2. ✅ Agregar Movimientos de Materiales desde Trazabilidad
3. ✅ Integrar Envíos Productor → Planta desde OrgTrack

### Fase 2: Evaluaciones y Adaptaciones (2-4 meses)
4. ⚠️ Evaluar migración de Lotes de Producción
5. ⚠️ Evaluar extensión de Almacenamiento en Trazabilidad
6. ⚠️ Agregar Gestión de Direcciones desde OrgTrack

### Fase 3: Microservicios Propios (4-6 meses)
7. 🔵 Crear Microservicio de Catálogos
8. 🔵 Crear Microservicio de Campo
9. 🔵 Crear Microservicio de Planta (con SPs)
10. 🔵 Crear Microservicio de Logística
11. 🔵 Crear Microservicio de Reportes

### Fase 4: Integración y Optimización (6+ meses)
12. 🔵 Integrar Certificaciones con Trazabilidad
13. 🔵 Evaluar Trazabilidad unificada
14. 🔵 Optimizar Dashboards consumiendo APIs

---

## 5. Consideraciones Técnicas

### Autenticación
- **Sistema Trazabilidad**: Usa JWT (`/api/auth/login`)
- **Sistema OrgTrack**: Endpoints públicos (sin autenticación)
- **Sistema Actual**: Laravel Auth (sesiones)
- **Acción**: Implementar autenticación unificada o adaptadores

### Estructura de Datos
- Los sistemas tienen estructuras de datos diferentes
- Requiere mapeo/adaptadores entre sistemas
- **Acción**: Crear capa de abstracción o DTOs

### Stored Procedures
- El sistema actual usa SPs de PostgreSQL
- Los microservicios usan lógica en aplicación
- **Acción**: Migrar lógica de SPs a microservicios o mantener SPs en microservicio

### Documentos PDF
- Ambos sistemas generan PDFs
- Formatos y contenido diferentes
- **Acción**: Estandarizar formatos o mantener ambos

---

## 6. Gaps Identificados

### Funcionalidades que faltan en los microservicios:

1. **Sistema Trazabilidad**:
   - ❌ Catálogos completos (departamentos, municipios, variedades, plantas, transportistas, almacenes, vehículos)
   - ❌ Gestión de campo (productores, lotes campo, sensores)
   - ❌ Transacciones complejas de almacén (recepciones, despachos con SPs)
   - ❌ Órdenes de envío Planta → Almacén
   - ❌ Reportes analíticos
   - ❌ Dashboards

2. **Sistema OrgTrack**:
   - ❌ Todo excepto envíos Productor → Planta y direcciones
   - ❌ No maneja flujo inverso (Planta → Almacén, Almacén → Cliente)

---

## 7. Conclusión Final

### Funcionalidades Reemplazables: **~15-20%**
- Pedidos de Cliente
- Movimientos de Materiales
- Envíos Productor → Planta
- Gestión de Direcciones

### Funcionalidades a Evaluar: **~10-15%**
- Lotes de Producción
- Almacenamiento
- Trazabilidad

### Funcionalidades a Mantener/Crear: **~65-75%**
- Catálogos Base
- Gestión de Campo
- Transacciones de Planta
- Logística (Órdenes Envío)
- Certificaciones
- Reportes
- Dashboards

### Recomendación General:
El sistema actual tiene **funcionalidad más amplia y específica** que los microservicios analizados. Los microservicios son más especializados en áreas específicas (trazabilidad de producción, envíos productor-planta).

**Estrategia sugerida**:
1. Consumir microservicios para funcionalidades que ya existen y son mejores
2. Crear microservicios propios para funcionalidades únicas
3. Mantener el API Gateway como orquestador que consume múltiples microservicios

---

**Fecha de Análisis**: 2024  
**Versión**: 1.0  
**Autor**: Análisis comparativo de sistemas

