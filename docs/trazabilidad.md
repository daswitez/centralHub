# Documentación de API - Trazabilidad

Este documento describe todas las rutas disponibles en la API de Trazabilidad, incluyendo detalles sobre los controladores, parámetros requeridos, y respuestas esperadas.

## Tabla de Contenidos

1. [Rutas Públicas](#rutas-públicas)
2. [Rutas Protegidas](#rutas-protegidas)
   - [Autenticación](#autenticación)
   - [CRUD de Recursos](#crud-de-recursos)
   - [Pedidos de Cliente](#pedidos-de-cliente)
   - [Productos](#productos)
   - [Aprobación de Pedidos](#aprobación-de-pedidos)
   - [Lotes de Producción](#lotes-de-producción)
   - [Transformación de Procesos](#transformación-de-procesos)
   - [Evaluación de Procesos](#evaluación-de-procesos)
   - [Almacenamiento](#almacenamiento)
   - [Movimientos de Materiales](#movimientos-de-materiales)
   - [Carga de Imágenes](#carga-de-imágenes)
3. [Rutas Legacy](#rutas-legacy)

---

## Rutas Públicas

Estas rutas no requieren autenticación mediante token JWT.

### Autenticación

#### `POST /api/auth/register`
Registra un nuevo operador en el sistema.

**Controlador:** `App\Http\Controllers\Api\AuthController@register`

**Parámetros:**
```json
{
  "nombre": "string (requerido, max:100)",
  "apellido": "string (requerido, max:100)",
  "usuario": "string (requerido, max:60, único)",
  "password": "string (requerido, min:6)",
  "email": "string (opcional, email, max:100)"
}
```

**Respuesta exitosa (201):**
```json
{
  "message": "Usuario registrado exitosamente",
  "operador_id": 1
}
```

**Errores:**
- `400`: Errores de validación
- `500`: Error al registrar usuario

---

#### `POST /api/auth/login`
Inicia sesión y obtiene un token JWT.

**Controlador:** `App\Http\Controllers\Api\AuthController@login`

**Parámetros:**
```json
{
  "username": "string (requerido)",
  "password": "string (requerido)"
}
```

**Respuesta exitosa (200):**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "operator": {
    "operador_id": 1,
    "nombre": "Juan",
    "apellido": "Pérez",
    "usuario": "jperez",
    "email": "juan@example.com"
  }
}
```

**Errores:**
- `400`: Errores de validación
- `401`: Credenciales inválidas
- `500`: Error al iniciar sesión

---

### Pedidos de Cliente (Públicos)

#### `POST /api/customer-orders`
Crea un nuevo pedido sin autenticación. Si hay token, usa el cliente del usuario autenticado; si no, crea/usa cliente basado en datos del body.

**Controlador:** `App\Http\Controllers\Api\CustomerOrderController@store`

**Parámetros:**
```json
{
  "nombre_usuario": "string (requerido, max:200)",
  "nombre": "string (requerido, max:200)",
  "fecha_entrega": "date (opcional)",
  "descripcion": "string (opcional)",
  "observaciones": "string (opcional)",
  "editable_hasta": "date (opcional, después de ahora)",
  "products": [
    {
      "producto_id": "integer (requerido, existe en producto)",
      "cantidad": "numeric (requerido, min:0.0001)",
      "observaciones": "string (opcional)"
    }
  ],
  "destinations": [
    {
      "direccion": "string (requerido, max:500)",
      "latitud": "numeric (opcional, -90 a 90)",
      "longitud": "numeric (opcional, -180 a 180)",
      "referencia": "string (opcional, max:200)",
      "nombre_contacto": "string (opcional, max:200)",
      "telefono_contacto": "string (opcional, max:20)",
      "instrucciones_entrega": "string (opcional)",
      "products": [
        {
          "order_product_index": "integer (requerido, min:0)",
          "cantidad": "numeric (requerido, min:0.0001)"
        }
      ]
    }
  ]
}
```

**Respuesta exitosa (201):**
```json
{
  "message": "Pedido creado exitosamente",
  "order": { ... }
}
```

---

#### `GET /api/customer-orders/by-user`
Obtiene pedidos por nombre de usuario sin autenticación.

**Controlador:** `App\Http\Controllers\Api\CustomerOrderController@byUser`

**Query Parameters:**
- `nombre_usuario` (requerido): Nombre de usuario del cliente

**Respuesta exitosa (200):**
```json
{
  "orders": [ ... ]
}
```

---

#### `PUT /api/customer-orders/{id}/public`
Actualiza un pedido sin autenticación. Valida que el `nombre_usuario` coincida con el cliente del pedido.

**Controlador:** `App\Http\Controllers\Api\CustomerOrderController@updatePublic`

**Parámetros:** (mismos que `POST /api/customer-orders`)

**Respuesta exitosa (200):**
```json
{
  "message": "Pedido actualizado exitosamente",
  "order": { ... }
}
```

---

### Productos (Públicos)

#### `GET /api/products`
Obtiene lista de productos disponibles. El token es opcional.

**Controlador:** `App\Http\Controllers\Api\ProductController@index`

**Query Parameters:**
- `tipo` (opcional): Filtrar por tipo (organico, marca_univalle, comestibles)
- `activo` (opcional): Filtrar por estado activo (true/false)
- `per_page` (opcional): Elementos por página (default: 15)

**Respuesta exitosa (200):**
```json
{
  "data": [ ... ],
  "current_page": 1,
  "per_page": 15,
  ...
}
```

---

#### `GET /api/products/{id}`
Obtiene un producto específico por ID.

**Controlador:** `App\Http\Controllers\Api\ProductController@show`

**Respuesta exitosa (200):**
```json
{
  "producto_id": 1,
  "codigo": "PROD001",
  "nombre": "Producto ejemplo",
  "tipo": "organico",
  "unit": { ... },
  ...
}
```

---

## Rutas Protegidas

Todas las rutas dentro de este grupo requieren autenticación mediante token JWT en el header:
```
Authorization: Bearer {token}
```

---

### Autenticación

#### `GET /api/auth/me`
Obtiene información del operador autenticado.

**Controlador:** `App\Http\Controllers\Api\AuthController@me`

**Respuesta exitosa (200):**
```json
{
  "operador_id": 1,
  "nombre": "Juan",
  "apellido": "Pérez",
  "usuario": "jperez",
  "email": "juan@example.com"
}
```

**Errores:**
- `404`: Usuario no encontrado
- `500`: Error al obtener información

---

#### `POST /api/auth/logout`
Cierra sesión invalidando el token JWT.

**Controlador:** `App\Http\Controllers\Api\AuthController@logout`

**Respuesta exitosa (200):**
```json
{
  "message": "Sesión cerrada exitosamente"
}
```

---

### CRUD de Recursos

Las siguientes rutas siguen el patrón REST estándar usando `apiResource`:

#### Unidades de Medida
- `GET /api/unit-of-measures` - Listar
- `POST /api/unit-of-measures` - Crear
- `GET /api/unit-of-measures/{id}` - Mostrar
- `PUT /api/unit-of-measures/{id}` - Actualizar
- `DELETE /api/unit-of-measures/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\UnitOfMeasureController`

---

#### Estados
- `GET /api/statuses` - Listar
- `POST /api/statuses` - Crear
- `GET /api/statuses/{id}` - Mostrar
- `PUT /api/statuses/{id}` - Actualizar
- `DELETE /api/statuses/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\StatusController`

---

#### Tipos de Movimiento
- `GET /api/movement-types` - Listar
- `POST /api/movement-types` - Crear
- `GET /api/movement-types/{id}` - Mostrar
- `PUT /api/movement-types/{id}` - Actualizar
- `DELETE /api/movement-types/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\MovementTypeController`

---

#### Roles de Operador
- `GET /api/operator-roles` - Listar
- `POST /api/operator-roles` - Crear
- `GET /api/operator-roles/{id}` - Mostrar
- `PUT /api/operator-roles/{id}` - Actualizar
- `DELETE /api/operator-roles/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\OperatorRoleController`

---

#### Clientes
- `GET /api/customers` - Listar
- `POST /api/customers` - Crear
- `GET /api/customers/{id}` - Mostrar
- `PUT /api/customers/{id}` - Actualizar
- `DELETE /api/customers/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\CustomerController`

---

#### Categorías de Materia Prima
- `GET /api/raw-material-categories` - Listar
- `POST /api/raw-material-categories` - Crear
- `GET /api/raw-material-categories/{id}` - Mostrar
- `PUT /api/raw-material-categories/{id}` - Actualizar
- `DELETE /api/raw-material-categories/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\RawMaterialCategoryController`

---

#### Proveedores
- `GET /api/suppliers` - Listar
- `POST /api/suppliers` - Crear
- `GET /api/suppliers/{id}` - Mostrar
- `PUT /api/suppliers/{id}` - Actualizar
- `DELETE /api/suppliers/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\SupplierController`

---

#### Variables Estandar
- `GET /api/standard-variables` - Listar
- `POST /api/standard-variables` - Crear
- `GET /api/standard-variables/{id}` - Mostrar
- `PUT /api/standard-variables/{id}` - Actualizar
- `DELETE /api/standard-variables/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\StandardVariableController`

---

#### Máquinas
- `GET /api/machines` - Listar
- `POST /api/machines` - Crear
- `GET /api/machines/{id}` - Mostrar
- `PUT /api/machines/{id}` - Actualizar
- `DELETE /api/machines/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\MachineController`

---

#### Procesos
- `GET /api/processes` - Listar
- `POST /api/processes` - Crear
- `GET /api/processes/{id}` - Mostrar
- `PUT /api/processes/{id}` - Actualizar
- `DELETE /api/processes/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\ProcessController`

---

#### Operadores
- `GET /api/operators` - Listar
- `POST /api/operators` - Crear
- `GET /api/operators/{id}` - Mostrar
- `PUT /api/operators/{id}` - Actualizar
- `DELETE /api/operators/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\OperatorController`

---

#### Bases de Materia Prima
- `GET /api/raw-material-bases` - Listar
- `POST /api/raw-material-bases` - Crear
- `GET /api/raw-material-bases/{id}` - Mostrar
- `PUT /api/raw-material-bases/{id}` - Actualizar
- `DELETE /api/raw-material-bases/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\RawMaterialBaseController`

---

#### Materias Primas
- `GET /api/raw-materials` - Listar
- `POST /api/raw-materials` - Crear
- `GET /api/raw-materials/{id}` - Mostrar
- `PUT /api/raw-materials/{id}` - Actualizar
- `DELETE /api/raw-materials/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\RawMaterialController`

**Parámetros para crear (`POST`):**
```json
{
  "material_id": "integer (requerido, existe en raw_material_base)",
  "supplier_id": "integer (requerido, existe en supplier)",
  "supplier_batch": "string (opcional, max:100)",
  "invoice_number": "string (opcional, max:100)",
  "receipt_date": "date (requerido)",
  "expiration_date": "date (opcional)",
  "quantity": "numeric (requerido, min:0)",
  "receipt_conformity": "boolean (opcional)",
  "observations": "string (opcional, max:500)"
}
```

---

### Pedidos de Cliente

#### `GET /api/customer-orders`
Lista todos los pedidos (requiere autenticación).

**Controlador:** `App\Http\Controllers\Api\CustomerOrderController@index`

**Query Parameters:**
- `per_page` (opcional): Elementos por página (default: 15)

**Respuesta exitosa (200):**
```json
{
  "data": [ ... ],
  "current_page": 1,
  ...
}
```

---

#### `GET /api/customer-orders/{id}`
Obtiene un pedido específico con todas sus relaciones.

**Controlador:** `App\Http\Controllers\Api\CustomerOrderController@show`

**Respuesta exitosa (200):**
```json
{
  "pedido_id": 1,
  "customer": { ... },
  "orderProducts": [ ... ],
  "destinations": [ ... ],
  ...
}
```

---

#### `PUT /api/customer-orders/{id}`
Actualiza un pedido. Solo permite editar si el pedido puede ser editado (no aprobado y dentro del tiempo de edición).

**Controlador:** `App\Http\Controllers\Api\CustomerOrderController@update`

**Parámetros:**
```json
{
  "name": "string (opcional, max:200)",
  "delivery_date": "date (opcional)",
  "description": "string (opcional)",
  "observations": "string (opcional)"
}
```

**Respuesta exitosa (200):**
```json
{
  "message": "Pedido actualizado exitosamente",
  "order": { ... }
}
```

**Errores:**
- `403`: El pedido no puede ser editado (ya aprobado o expiró tiempo de edición)

---

#### `DELETE /api/customer-orders/{id}`
Elimina un pedido. Solo si está pendiente y puede ser editado.

**Controlador:** `App\Http\Controllers\Api\CustomerOrderController@destroy`

**Respuesta exitosa (200):**
```json
{
  "message": "Pedido eliminado exitosamente"
}
```

**Errores:**
- `403`: El pedido no puede ser eliminado

---

#### `POST /api/customer-orders/{id}/cancel`
Cancela un pedido.

**Controlador:** `App\Http\Controllers\Api\CustomerOrderController@cancel`

**Respuesta exitosa (200):**
```json
{
  "message": "Pedido cancelado exitosamente"
}
```

---

### Productos

#### `POST /api/products`
Crea un nuevo producto.

**Controlador:** `App\Http\Controllers\Api\ProductController@store`

**Parámetros:**
```json
{
  "codigo": "string (requerido, max:50, único)",
  "nombre": "string (requerido, max:200)",
  "tipo": "string (requerido, in:organico,marca_univalle,comestibles)",
  "peso": "numeric (opcional, min:0)",
  "unidad_id": "integer (opcional, existe en unidad_medida)",
  "descripcion": "string (opcional)",
  "activo": "boolean (opcional)"
}
```

**Respuesta exitosa (201):**
```json
{
  "message": "Producto creado exitosamente",
  "product": { ... }
}
```

---

#### `PUT /api/products/{id}`
Actualiza un producto existente.

**Controlador:** `App\Http\Controllers\Api\ProductController@update`

**Parámetros:** (mismos que `POST`, todos opcionales)

**Respuesta exitosa (200):**
```json
{
  "message": "Producto actualizado exitosamente",
  "product": { ... }
}
```

---

#### `DELETE /api/products/{id}`
Elimina un producto.

**Controlador:** `App\Http\Controllers\Api\ProductController@destroy`

**Respuesta exitosa (200):**
```json
{
  "message": "Producto eliminado exitosamente"
}
```

---

### Aprobación de Pedidos

#### `GET /api/order-approval/pending`
Obtiene pedidos pendientes de aprobación.

**Controlador:** `App\Http\Controllers\Api\OrderApprovalController@pendingOrders`

**Query Parameters:**
- `per_page` (opcional): Elementos por página (default: 15)

**Respuesta exitosa (200):**
```json
{
  "data": [ ... ],
  ...
}
```

---

#### `GET /api/order-approval/{id}`
Obtiene detalles de un pedido para aprobación.

**Controlador:** `App\Http\Controllers\Api\OrderApprovalController@show`

**Respuesta exitosa (200):**
```json
{
  "pedido_id": 1,
  "customer": { ... },
  "orderProducts": [ ... ],
  "destinations": [ ... ],
  ...
}
```

---

#### `POST /api/order-approval/{orderId}/approve`
Aprueba todo el pedido (todos los productos pendientes).

**Controlador:** `App\Http\Controllers\Api\OrderApprovalController@approveOrder`

**Parámetros:**
```json
{
  "observations": "string (opcional, max:500)"
}
```

**Respuesta exitosa (200):**
```json
{
  "message": "Pedido aprobado exitosamente",
  "order": { ... }
}
```

**Errores:**
- `400`: No hay productos pendientes para aprobar

---

#### `POST /api/order-approval/{orderId}/product/{productId}/approve`
Aprueba un producto específico del pedido.

**Controlador:** `App\Http\Controllers\Api\OrderApprovalController@approveProduct`

**Parámetros:**
```json
{
  "observations": "string (opcional, max:500)"
}
```

**Respuesta exitosa (200):**
```json
{
  "message": "Producto aprobado exitosamente",
  "order_product": { ... }
}
```

**Nota:** Si todos los productos quedan aprobados, el pedido completo se aprueba automáticamente.

---

#### `POST /api/order-approval/{orderId}/product/{productId}/reject`
Rechaza un producto específico del pedido.

**Controlador:** `App\Http\Controllers\Api\OrderApprovalController@rejectProduct`

**Parámetros:**
```json
{
  "rejection_reason": "string (requerido, max:500)"
}
```

**Respuesta exitosa (200):**
```json
{
  "message": "Producto rechazado exitosamente",
  "order_product": { ... }
}
```

---

### Lotes de Producción

#### `GET /api/production-batches`
Lista todos los lotes de producción.

**Controlador:** `App\Http\Controllers\Api\ProductionBatchController@index`

**Query Parameters:**
- `per_page` (opcional): Elementos por página

**Respuesta exitosa (200):**
```json
{
  "data": [ ... ],
  ...
}
```

---

#### `POST /api/production-batches`
Crea un nuevo lote de producción.

**Controlador:** `App\Http\Controllers\Api\ProductionBatchController@store`

**Parámetros:**
```json
{
  "order_id": "integer (requerido, existe en customer_order)",
  "batch_number": "string (opcional)",
  "production_date": "date (opcional)",
  "quantity": "numeric (requerido, min:0)",
  "observations": "string (opcional)"
}
```

**Respuesta exitosa (201):**
```json
{
  "message": "Lote creado exitosamente",
  "batch": { ... }
}
```

---

#### `GET /api/production-batches/{id}`
Obtiene un lote específico.

**Controlador:** `App\Http\Controllers\Api\ProductionBatchController@show`

---

#### `PUT /api/production-batches/{id}`
Actualiza un lote.

**Controlador:** `App\Http\Controllers\Api\ProductionBatchController@update`

---

#### `DELETE /api/production-batches/{id}`
Elimina un lote (solo si no tiene registros de proceso).

**Controlador:** `App\Http\Controllers\Api\ProductionBatchController@destroy`

---

#### `GET /api/batches/pending-certification`
Obtiene lotes pendientes de certificación.

**Controlador:** `App\Http\Controllers\Api\ProductionBatchController@getPendingCertification`

**Respuesta exitosa (200):**
```json
[
  {
    "batch_id": 1,
    "order": { ... },
    "processMachineRecords": [ ... ],
    "finalEvaluation": { ... },
    ...
  }
]
```

---

#### `POST /api/batches/{batchId}/assign-process`
Asigna un proceso a un lote.

**Controlador:** `App\Http\Controllers\Api\ProductionBatchController@assignProcess`

**Parámetros:**
```json
{
  "process_id": "integer (requerido, existe en proceso)",
  // O alternativamente:
  "proceso_id": "integer (requerido, existe en proceso)"
}
```

**Respuesta exitosa (200):**
```json
{
  "message": "Proceso asignado exitosamente",
  "process_id": 1,
  "process_machines": [ ... ],
  "completed_records": []
}
```

**Errores:**
- `400`: El proceso no tiene máquinas configuradas
- `400`: El lote ya tiene registros de otro proceso

---

#### `GET /api/batches/{batchId}/process-machines`
Obtiene las máquinas del proceso asignado a un lote.

**Controlador:** `App\Http\Controllers\Api\ProductionBatchController@getProcessMachines`

**Query Parameters:**
- `process_id` (opcional): ID del proceso (si no se proporciona, se obtiene de los registros existentes)

**Respuesta exitosa (200):**
```json
{
  "process_machines": [ ... ],
  "completed_records": [1, 2, 3],
  "process_id": 1
}
```

---

#### `POST /api/batches/{batchId}/finalize-certification`
Finaliza la certificación de un lote. Verifica que todos los registros de máquinas estén completos.

**Controlador:** `App\Http\Controllers\Api\ProductionBatchController@finalizeCertification`

**Respuesta exitosa (200):**
```json
{
  "message": "Certificado - El proceso ha sido finalizado",
  "status": "Certificado",
  "reason": "Todas las máquinas cumplen los valores estándar"
}
```

**Errores:**
- `400`: El lote no tiene registros de proceso
- `400`: Faltan formularios (no todas las máquinas están completas)
- `400`: El lote tiene registros de múltiples procesos

---

#### `GET /api/batches/{batchId}/certification-log`
Obtiene el log de certificación de un lote.

**Controlador:** `App\Http\Controllers\Api\ProductionBatchController@getCertificationLog`

**Respuesta exitosa (200):**
```json
{
  "batch": { ... },
  "records": [ ... ],
  "evaluation": { ... }
}
```

---

### Transformación de Procesos

#### `POST /api/process-transformation/batch/{batchId}/machine/{processMachineId}`
Registra un formulario de transformación para una máquina en un lote.

**Controlador:** `App\Http\Controllers\Api\ProcessTransformationController@registerForm`

**Parámetros:**
```json
{
  "entered_variables": [
    {
      "variable_id": "integer (requerido)",
      "value": "numeric (requerido)",
      "meets_standard": "boolean (opcional)"
    }
  ],
  "observations": "string (opcional, max:500)",
  "start_time": "date (opcional)",
  "end_time": "date (opcional)"
}
```

**Respuesta exitosa (201):**
```json
{
  "message": "Formulario registrado exitosamente",
  "record_id": 1
}
```

**Nota:** El operador se obtiene del token de autenticación.

---

#### `GET /api/process-transformation/batch/{batchId}/machine/{processMachineId}`
Obtiene el formulario de transformación registrado para una máquina en un lote.

**Controlador:** `App\Http\Controllers\Api\ProcessTransformationController@getForm`

**Respuesta exitosa (200):**
```json
{
  "record_id": 1,
  "processMachine": { ... },
  "operator": { ... },
  "entered_variables": [ ... ],
  ...
}
```

**Errores:**
- `404`: Formulario no encontrado

---

#### `GET /api/process-transformation/batch/{batchId}`
Obtiene información del proceso de un lote.

**Controlador:** `App\Http\Controllers\Api\ProcessTransformationController@getBatchProcess`

**Respuesta exitosa (200):**
```json
{
  "batch": { ... },
  "process_machines": [ ... ],
  ...
}
```

---

### Evaluación de Procesos

#### `POST /api/process-evaluation/finalize/{batchId}`
Finaliza y evalúa un proceso de lote.

**Controlador:** `App\Http\Controllers\Api\ProcessEvaluationController@finalize`

**Parámetros:**
```json
{
  "reason": "string (opcional, max:500)",
  "observations": "string (opcional, max:500)"
}
```

**Respuesta exitosa (200):**
```json
{
  "message": "Certificado el proceso ha sido finalizado",
  "motivo": "Todas las máquinas cumplen los valores estándar",
  "evaluation_id": 1
}
```

**Errores:**
- `400`: Faltan formularios (no todas las máquinas están completas)

---

#### `GET /api/process-evaluation/log/{batchId}`
Obtiene el log de evaluación de un lote.

**Controlador:** `App\Http\Controllers\Api\ProcessEvaluationController@getLog`

**Respuesta exitosa (200):**
```json
{
  "batch": { ... },
  "evaluation": { ... },
  "records": [ ... ]
}
```

**Errores:**
- `404`: El lote aún no ha sido evaluado

---

### Almacenamiento

#### `GET /api/storages`
Lista todos los registros de almacenamiento.

**Controlador:** `App\Http\Controllers\Api\StorageController@index`

**Query Parameters:**
- `per_page` (opcional): Elementos por página (default: 15)

**Respuesta exitosa (200):**
```json
{
  "data": [ ... ],
  ...
}
```

---

#### `POST /api/storages`
Crea un nuevo registro de almacenamiento.

**Controlador:** `App\Http\Controllers\Api\StorageController@store`

**Parámetros:**
```json
{
  "batch_id": "integer (requerido, existe en production_batch)",
  "location": "string (requerido, max:100)",
  "condition": "string (requerido, max:100)",
  "quantity": "numeric (requerido, min:0)",
  "observations": "string (opcional, max:500)"
}
```

**Respuesta exitosa (201):**
```json
{
  "id": 1,
  "message": "Almacenaje registrado y lote actualizado"
}
```

---

#### `GET /api/storages/batch/{batchId}`
Obtiene el almacenamiento de un lote específico.

**Controlador:** `App\Http\Controllers\Api\StorageController@getByBatch`

**Respuesta exitosa (200):**
```json
{
  "storage_id": 1,
  "batch": { ... },
  ...
}
```

**Errores:**
- `404`: Almacenaje no encontrado para este lote

---

### Movimientos de Materiales

#### `GET /api/material-movement-logs`
Lista todos los logs de movimiento de materiales.

**Controlador:** `App\Http\Controllers\Api\MaterialMovementLogController@index`

**Query Parameters:**
- `per_page` (opcional): Elementos por página (default: 15)

---

#### `POST /api/material-movement-logs`
Crea un nuevo log de movimiento de material.

**Controlador:** `App\Http\Controllers\Api\MaterialMovementLogController@store`

**Parámetros:**
```json
{
  "material_id": "integer (requerido, existe en raw_material_base)",
  "movement_type_id": "integer (requerido, existe en movement_type)",
  "quantity": "numeric (requerido)",
  "description": "string (opcional, max:500)"
}
```

**Respuesta exitosa (201):**
```json
{
  "message": "Log creado exitosamente",
  "log_id": 1
}
```

**Nota:** 
- El `user_id` se obtiene del token de autenticación
- Si el tipo de movimiento afecta el stock, se actualiza automáticamente la cantidad disponible del material

---

#### `GET /api/material-movement-logs/material/{materialId}`
Obtiene todos los logs de movimiento de un material específico.

**Controlador:** `App\Http\Controllers\Api\MaterialMovementLogController@getByMaterial`

**Respuesta exitosa (200):**
```json
[
  {
    "log_id": 1,
    "movementType": { ... },
    "user": { ... },
    ...
  }
]
```

---

### Carga de Imágenes

#### `POST /api/upload`
Sube una imagen a Cloudinary.

**Controlador:** `App\Http\Controllers\Web\ImageUploadController@upload`

**Parámetros (multipart/form-data):**
- `image` o `imagen` (requerido): Archivo de imagen (jpeg, jpg, png, max: 5MB)
- `folder` (opcional): Carpeta en Cloudinary (default: "trazabilidad")

**Respuesta exitosa (200):**
```json
{
  "success": true,
  "imageUrl": "https://res.cloudinary.com/...",
  "publicId": "trazabilidad/..."
}
```

**Errores:**
- `400`: No se recibió ningún archivo o error de validación
- `500`: Cloudinary no está configurado o error al subir

**Nota:** Requiere configuración de Cloudinary en variables de entorno:
- `CLOUDINARY_CLOUD_NAME`
- `CLOUDINARY_API_KEY`
- `CLOUDINARY_API_SECRET`

---

### Otros Recursos CRUD

#### Lotes de Materia Prima
- `GET /api/batch-raw-materials` - Listar
- `POST /api/batch-raw-materials` - Crear
- `GET /api/batch-raw-materials/{id}` - Mostrar
- `PUT /api/batch-raw-materials/{id}` - Actualizar
- `DELETE /api/batch-raw-materials/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\BatchRawMaterialController`

---

#### Máquinas de Proceso
- `GET /api/process-machines` - Listar
- `POST /api/process-machines` - Crear
- `GET /api/process-machines/{id}` - Mostrar
- `PUT /api/process-machines/{id}` - Actualizar
- `DELETE /api/process-machines/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\ProcessMachineController`

---

#### Variables de Máquina de Proceso
- `GET /api/process-machine-variables` - Listar
- `POST /api/process-machine-variables` - Crear
- `GET /api/process-machine-variables/{id}` - Mostrar
- `PUT /api/process-machine-variables/{id}` - Actualizar
- `DELETE /api/process-machine-variables/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\ProcessMachineVariableController`

---

#### Registros de Máquina de Proceso
- `GET /api/process-machine-records` - Listar
- `POST /api/process-machine-records` - Crear
- `GET /api/process-machine-records/{id}` - Mostrar
- `PUT /api/process-machine-records/{id}` - Actualizar
- `DELETE /api/process-machine-records/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\ProcessMachineRecordController`

---

#### Evaluaciones Finales de Proceso
- `GET /api/process-final-evaluations` - Listar
- `POST /api/process-final-evaluations` - Crear
- `GET /api/process-final-evaluations/{id}` - Mostrar
- `PUT /api/process-final-evaluations/{id}` - Actualizar
- `DELETE /api/process-final-evaluations/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\ProcessFinalEvaluationController`

---

#### Solicitudes de Material
- `GET /api/material-requests` - Listar
- `POST /api/material-requests` - Crear
- `GET /api/material-requests/{id}` - Mostrar
- `PUT /api/material-requests/{id}` - Actualizar
- `DELETE /api/material-requests/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\MaterialRequestController`

---

#### Detalles de Solicitud de Material
- `GET /api/material-request-details` - Listar
- `POST /api/material-request-details` - Crear
- `GET /api/material-request-details/{id}` - Mostrar
- `PUT /api/material-request-details/{id}` - Actualizar
- `DELETE /api/material-request-details/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\MaterialRequestDetailController`

---

#### Respuestas de Proveedor
- `GET /api/supplier-responses` - Listar
- `POST /api/supplier-responses` - Crear
- `GET /api/supplier-responses/{id}` - Mostrar
- `PUT /api/supplier-responses/{id}` - Actualizar
- `DELETE /api/supplier-responses/{id}` - Eliminar

**Controlador:** `App\Http\Controllers\Api\SupplierResponseController`

---

## Rutas Legacy

Estas rutas se mantienen para compatibilidad con sistemas anteriores:

#### `GET /api/operadores`
Lista operadores (ruta legacy en español).

**Controlador:** `App\Http\Controllers\OperadorController`

---

#### `GET /api/proveedores`
Lista proveedores (ruta legacy en español).

**Controlador:** `App\Http\Controllers\ProveedorController`

---

## Notas Generales

### Autenticación
- Las rutas protegidas requieren un token JWT en el header: `Authorization: Bearer {token}`
- El token se obtiene mediante `POST /api/auth/login`
- El token puede invalidarse con `POST /api/auth/logout`

### Paginación
- Muchas rutas de listado aceptan el parámetro `per_page` para controlar el número de elementos por página
- El valor por defecto suele ser 15 elementos

### Validación
- Todas las rutas validan los datos de entrada
- Los errores de validación devuelven código `400` o `422` con detalles de los errores

### Respuestas de Error
Las respuestas de error generalmente siguen este formato:
```json
{
  "message": "Mensaje de error descriptivo",
  "error": "Detalle técnico del error (en desarrollo)",
  "errors": { ... } // Solo en errores de validación
}
```

### Códigos de Estado HTTP
- `200`: Éxito
- `201`: Creado exitosamente
- `400`: Error de validación o solicitud incorrecta
- `401`: No autenticado
- `403`: No autorizado
- `404`: Recurso no encontrado
- `500`: Error interno del servidor

---

## Flujo de Trabajo Típico

1. **Registro/Login**: `POST /api/auth/register` o `POST /api/auth/login`
2. **Crear Pedido**: `POST /api/customer-orders` (público, pero puede usar token)
3. **Aprobar Pedido**: `POST /api/order-approval/{id}/approve` (requiere autenticación)
4. **Crear Lote**: `POST /api/production-batches`
5. **Asignar Proceso**: `POST /api/batches/{batchId}/assign-process`
6. **Registrar Transformaciones**: `POST /api/process-transformation/batch/{batchId}/machine/{processMachineId}` (para cada máquina)
7. **Finalizar Certificación**: `POST /api/batches/{batchId}/finalize-certification`
8. **Almacenar Lote**: `POST /api/storages`

---

*Última actualización: Basado en `routes/api.php` líneas 1-120*

