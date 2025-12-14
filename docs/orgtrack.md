# Documentación - EnvioPublicoController

## Contexto del Proyecto

**OrgTrack** es un sistema de seguimiento de transporte de **Productor a Planta**.

### Plataforma Web (Clientes y Admin)
- Los clientes pueden crear envíos completando un formulario con origen y destino
- El administrador revisa las solicitudes y asigna transporte y vehículo
- Ambos pueden ver el estado del envío: **Pendiente**, **En curso** y **Finalizado**
- Se pueden guardar direcciones para reutilizarlas
- Al completar el envío, se genera documentación con toda la información + firmas

### Aplicación Móvil
- **Cliente**: Mismas funciones que en web
- **Transportista**: 
  - Ve los envíos asignados
  - Rellena registro de condiciones antes de iniciar
  - Inicia el viaje (estado "En curso")
  - Al finalizar: llena registro de incidencias + firma digital
  - Muestra QR al cliente para que acceda a la documentación y firme

---

## Resumen de Endpoints

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/publico/direccion` | Crear dirección de productor |
| POST | `/api/publico/envio` | Crear envío de productor |
| GET | `/api/publico/envios` | Listar todos los envíos públicos |
| GET | `/api/publico/envio/{id}` | Obtener envío público por ID |
| GET | `/api/publico/envios-productores` | Listar envíos entregados |
| GET | `/api/publico/documento/{id_envio}` | Obtener documento de envío |

---

## 1. Crear Dirección de Productor

**POST** `/api/publico/direccion`

### Request JSON:
```json
{
    "nombreorigen": "Finca El Roble, Santa Cruz",
    "nombredestino": "Mercado Central, La Paz",
    "origen_lat": -17.7833,
    "origen_lng": -63.1821,
    "destino_lat": -16.5000,
    "destino_lng": -68.1500,
    "rutageojson": null
}
```

### Response JSON (201):
```json
{
    "mensaje": "Dirección creada exitosamente",
    "id_direccion": 15
}
```

---

## 2. Crear Envío de Productor

**POST** `/api/publico/envio`

### Request JSON:
```json
{
    "nombre_remitente": "Juan Pérez",
    "telefono_remitente": "+591 70012345",
    "email_remitente": "juan@email.com",
    "id_direccion": 15,
    "particiones": [
        {
            "id_tipo_transporte": 1,
            "cargas": [
                {
                    "id_categoria": 1,
                    "id_producto": 5,
                    "id_tipo_empaque": 3,
                    "cantidad": 100,
                    "peso": 2500,
                    "conteo_por_empaque": 25,
                    "peso_promedio_unidad": 0.5,
                    "capacidad_por_empaque": 30,
                    "largo_cm": 40,
                    "ancho_cm": 30,
                    "alto_cm": 20,
                    "peso_neto_kg": 25,
                    "tara_kg": 2,
                    "peso_bruto_kg": 27,
                    "forma_pedido": "cajas",
                    "cantidad_pedido": 100,
                    "empaques_calculados": 4,
                    "unidades_por_pallet": 50,
                    "numero_pallets": 2
                }
            ],
            "recogidaEntrega": {
                "fecha_recogida": "2025-12-15",
                "hora_recogida": "08:00",
                "hora_entrega": "14:00",
                "instrucciones_recogida": "Tocar bocina",
                "instrucciones_entrega": "Entregar en bodega"
            }
        }
    ]
}
```

### Response JSON (201):
```json
{
    "mensaje": "Envío de productor creado exitosamente",
    "id_envio": 42
}
```

---

## 3. Listar Todos los Envíos Públicos

**GET** `/api/publico/envios`

### Response JSON (200):
```json
[
    {
        "id": 42,
        "nombre_remitente": "Juan Pérez",
        "telefono_remitente": "+591 70012345",
        "email_remitente": "juan@email.com",
        "estado": "En curso",
        "fecha_creacion": "2025-12-10 14:30:00",
        "fecha_inicio": "2025-12-12 08:00:00",
        "fecha_entrega": null,
        "direccion_origen": "Finca El Roble",
        "direccion_destino": "Mercado Central",
        "numero_solicitud": "SOL-2025-001",
        "fecha_requerida": "2025-12-20",
        "prioridad": 5,
        "observaciones_solicitud": "Urgente",
        "cancelado": false
    }
]
```

---

## 4. Obtener Envío Público por ID

**GET** `/api/publico/envio/{id}`

### Response JSON (200):
```json
{
    "id": 42,
    "nombre_remitente": "Juan Pérez",
    "telefono_remitente": "+591 70012345",
    "email_remitente": "juan@email.com",
    "estado": "En curso",
    "fecha_creacion": "2025-12-10 14:30:00",
    "fecha_inicio": "2025-12-12 08:00:00",
    "fecha_entrega": null,
    "numero_solicitud": "SOL-2025-001",
    "fecha_requerida": "2025-12-20",
    "prioridad": 5,
    "observaciones_solicitud": "Urgente",
    "cancelado": false,
    "coordenadas_origen": {
        "lng": -63.1821,
        "lat": -17.7833
    },
    "coordenadas_destino": {
        "lng": -68.1500,
        "lat": -16.5000
    },
    "nombre_origen": "Finca El Roble",
    "nombre_destino": "Mercado Central",
    "rutaGeoJSON": null,
    "particiones": [
        {
            "id_asignacion": 101,
            "codigo_acceso": "A1B2C3",
            "id_transportista": 5,
            "id_vehiculo": 8,
            "estado": "En curso",
            "fecha_asignacion": "2025-12-11",
            "fecha_inicio": "2025-12-12 08:15:00",
            "fecha_fin": null,
            "transportista": {
                "nombre": "Carlos",
                "apellido": "Mamani",
                "telefono": "+591 72233445",
                "ci": "1234567"
            },
            "vehiculo": {
                "placa": "ABC-123",
                "tipo": "Camión mediano"
            },
            "tipoTransporte": {
                "nombre": "Refrigerado",
                "descripcion": "Transporte con temperatura controlada"
            },
            "recogidaEntrega": {
                "fecha_recogida": "2025-12-12",
                "hora_recogida": "08:00",
                "hora_entrega": "14:00",
                "instrucciones_recogida": "Tocar bocina",
                "instrucciones_entrega": "Entregar en bodega"
            },
            "cargas": [
                {
                    "id": 201,
                    "tipo": "Frutas",
                    "variedad": "Manzana Gala",
                    "empaquetado": "Caja de cartón",
                    "cantidad": 100,
                    "peso": 2500
                }
            ]
        }
    ],
    "estado_resumen": "En curso (1 de 1 camiones activos)"
}
```

---

## 5. Listar Envíos de Productores Entregados

**GET** `/api/publico/envios-productores`

### Response JSON (200):
```json
[
    {
        "id": 41,
        "nombre_remitente": "María González",
        "telefono_remitente": "+591 71234567",
        "email_remitente": null,
        "estado": "Entregado",
        "fecha_creacion": "2025-12-08T10:15:00.000000Z",
        "fecha_entrega": "2025-12-09 15:45:00",
        "nombre_origen": "Granja Los Pinos",
        "nombre_destino": "Supermercado Plaza"
    }
]
```

---

## 6. Obtener Documento de Productor

**GET** `/api/publico/documento/{id_envio}`

> Solo funciona para envíos con estado "Entregado" o "Parcialmente entregado"

### Response JSON (200):
```json
{
    "id_envio": 41,
    "nombre_cliente": "María González",
    "estado": "Entregado",
    "fecha_creacion": "2025-12-08T10:15:00.000000Z",
    "fecha_inicio": "2025-12-09T07:30:00.000000Z",
    "fecha_entrega": "2025-12-09T15:45:00.000000Z",
    "nombre_origen": "Granja Los Pinos",
    "nombre_destino": "Supermercado Plaza",
    "particiones": [
        {
            "id_asignacion": 95,
            "estado": "Entregado",
            "fecha_asignacion": "2025-12-08",
            "fecha_inicio": "2025-12-09 07:30:00",
            "fecha_fin": "2025-12-09 15:45:00",
            "transportista": {
                "nombre": "Carlos",
                "apellido": "Mamani",
                "telefono": "+591 72233445",
                "ci": "1234567"
            },
            "vehiculo": {
                "placa": "ABC-123",
                "tipo": "Camión mediano"
            },
            "tipo_transporte": {
                "nombre": "Refrigerado",
                "descripcion": "Transporte con temperatura controlada"
            },
            "recogidaEntrega": {
                "fecha_recogida": "2025-12-09",
                "hora_recogida": "07:30",
                "hora_entrega": "15:00",
                "instrucciones_recogida": null,
                "instrucciones_entrega": "Entregar en recepción"
            },
            "cargas": [
                {
                    "id": 180,
                    "tipo": "Verduras",
                    "variedad": "Lechuga Romana",
                    "empaquetado": "Bolsa plástica",
                    "cantidad": 200,
                    "peso": 400
                }
            ],
            "checklistCondiciones": [
                {
                    "id": 1,
                    "condicion": {
                        "id": 1,
                        "titulo": "Temperatura adecuada"
                    },
                    "cumple": true,
                    "observacion": null
                }
            ],
            "observaciones_condiciones": "Todo en buen estado",
            "checklistIncidentes": [
                {
                    "id": 1,
                    "tipo_incidente": {
                        "id": 1,
                        "titulo": "Retraso en entrega"
                    },
                    "ocurrio": false,
                    "descripcion": null
                }
            ],
            "observaciones_incidentes": null,
            "firmaTransportista": "data:image/png;base64,...",
            "firma": "data:image/png;base64,..."
        }
    ]
}
```

---

## Configuración de Rutas (routes/api.php)

```php
use App\Http\Controllers\Api\EnvioPublicoController;

Route::prefix('publico')->group(function () {
    Route::post('/direccion', [EnvioPublicoController::class, 'crearDireccionProductor']);
    Route::post('/envio', [EnvioPublicoController::class, 'crearEnvioProductor']);
    Route::get('/envios', [EnvioPublicoController::class, 'listarTodosEnviosPublicos']);
    Route::get('/envio/{id}', [EnvioPublicoController::class, 'obtenerEnvioPublicoPorId']);
    Route::get('/envios-productores', [EnvioPublicoController::class, 'listarEnviosProductores']);
    Route::get('/documento/{id_envio}', [EnvioPublicoController::class, 'obtenerDocumentoProductor']);
});
```
