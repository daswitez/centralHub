## Uso de los endpoints CRUD desde una app React Native

Esta guía muestra **cómo consumir los endpoints de catálogos (`/cat`) desde una app React Native**, usando `fetch` y TypeScript.

- **Backend base**: Laravel + PostgreSQL (este proyecto).
- **URL base por defecto**: `http://127.0.0.1:8000` (ajústala según tu entorno).
- **Catálogos cubiertos**:
  - Departamentos
  - Municipios
  - Variedades de papa

> Nota rápida: en emulador Android, `http://10.0.2.2:8000` apunta al host; en dispositivo físico usa la IP de tu PC (por ejemplo `http://192.168.0.10:8000`).

---

### 1) Cliente HTTP base en React Native

Ejemplo de archivo compartido `apiClient.ts` para toda la app:

```ts
// apiClient.ts
// -----------------------------------------
// Cliente HTTP reutilizable para hablar con el backend Laravel
// -----------------------------------------

// URL base del backend Laravel (cambia según tu entorno)
// IMPORTANTE:
// - Siempre apunta al puerto 8000 (php artisan serve --host=0.0.0.0 --port=8000)
// - Todas las rutas móviles usan el prefijo /api (grupo "api" de Laravel)
export const API_BASE_URL = 'http://127.0.0.1:8000/api';

// IMPORTANTE:
// - El backend Laravel sirve HTML (vistas Blade) por defecto en /cat, /campo, /tx.
// - En el grupo /api (rutas de este documento), las peticiones son "stateless"
//   y no requieren CSRF ni sesión, pensadas para móvil.
// - Cuando la petición envía Accept: application/json,
//   los controladores devuelven JSON con la forma:
//   { status: 'ok' | 'error', message: string, data?: any, ... }
// - La app móvil DEBE hablar siempre con el puerto 8000
//   (php artisan serve --host=0.0.0.0 --port=8000), nunca con Vite (5173).

// Tipo genérico para errores de API
export type ApiError = {
  message: string;
  status?: number;
  details?: unknown;
};

// Helper genérico para hacer requests y devolver JSON tipado
export const handleApiRequest = async <TResponse>(
  endpointPath: string,
  options: RequestInit = {}
): Promise<TResponse> => {
  // Construimos la URL completa usando la base
  const fullUrl = `${API_BASE_URL}${endpointPath}`;

  // Mezclamos opciones con cabeceras por defecto orientadas a JSON
  const mergedOptions: RequestInit = {
    method: 'GET',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      ...(options.headers || {}),
    },
    ...options,
  };

  // Lanzamos el request HTTP hacia Laravel
  const response = await fetch(fullUrl, mergedOptions);

  // Si el status no está en el rango 200-299, lanzamos error
  if (!response.ok) {
    let errorBody: unknown = undefined;
    try {
      errorBody = await response.json();
    } catch {
      // Si no se puede parsear JSON, simplemente lo dejamos como undefined
    }

    const apiError: ApiError = {
      message: `Error HTTP ${response.status}`,
      status: response.status,
      details: errorBody,
    };

    throw apiError;
  }

  // Devolvemos la respuesta ya parseada como JSON del tipo esperado
  return (await response.json()) as TResponse;
};
```

---

### 2) Tipos TypeScript para los catálogos

```ts
// catalogTypes.ts
// -----------------------------------------
// Tipos compartidos para los catálogos base
// -----------------------------------------

// Departamento simple
export type Departamento = {
  id: number;
  nombre: string;
  created_at?: string;
  updated_at?: string;
};

// Municipio ligado a un departamento
export type Municipio = {
  id: number;
  departamento_id: number;
  nombre: string;
  created_at?: string;
  updated_at?: string;
};

// Variedad de papa con rango de días de ciclo
export type VariedadPapa = {
  variedad_id: number;
  codigo_variedad: string;
  nombre_comercial: string;
  aptitud?: string | null;
  ciclo_dias_min?: number | null;
  ciclo_dias_max?: number | null;
  created_at?: string;
  updated_at?: string;
};
```

---

### 3) Endpoints de Departamentos (`/cat/departamentos`)

Del README del proyecto:

- `GET /cat/departamentos` (index, filtro `?q=`)
- `POST /cat/departamentos`
- `PUT /cat/departamentos/{id}`
- `DELETE /cat/departamentos/{id}`

> Importante: asegúrate de que el backend soporte **respuestas JSON** para peticiones con cabecera `Accept: application/json`.  
> Si por defecto devuelve vistas Blade, puedes necesitar exponer rutas API adicionales (`Route::apiResource`) que devuelvan JSON puro.

#### 3.1) Listar departamentos con filtro opcional

```ts
// departamentosApi.ts
// -----------------------------------------
// Funciones CRUD para Departamentos
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type { Departamento } from './catalogTypes';

// Lista departamentos, con filtro opcional por nombre (?q=)
export const getDepartamentos = async (
  searchText?: string
): Promise<Departamento[]> => {
  // Construimos el query string solo si hay filtro
  const query = searchText ? `?q=${encodeURIComponent(searchText)}` : '';

  // Hacemos un GET al endpoint de index
  const departamentos = await handleApiRequest<Departamento[]>(
    `/cat/departamentos${query}`,
    {
      method: 'GET',
    }
  );

  // Devolvemos la lista de departamentos tipada
  return departamentos;
};
```

#### 3.2) Crear un departamento

```ts
// Crea un nuevo departamento enviando JSON al backend
export const createDepartamento = async (
  nombre: string
): Promise<Departamento> => {
  // Cuerpo JSON que Laravel espera según el README
  const body = { nombre };

  // POST hacia el endpoint de creación
  const nuevoDepartamento = await handleApiRequest<Departamento>(
    '/cat/departamentos',
    {
      method: 'POST',
      body: JSON.stringify(body),
    }
  );

  // Devolvemos el departamento creado
  return nuevoDepartamento;
};
```

#### 3.3) Actualizar un departamento

```ts
// Actualiza un departamento existente usando su id
export const updateDepartamento = async (
  id: number,
  nombre: string
): Promise<Departamento> => {
  // Cuerpo de datos actualizado
  const body = { nombre };

  // PUT al endpoint específico del recurso
  const updatedDepartamento = await handleApiRequest<Departamento>(
    `/cat/departamentos/${id}`,
    {
      method: 'PUT',
      body: JSON.stringify(body),
    }
  );

  // Devolvemos el departamento ya actualizado
  return updatedDepartamento;
};
```

#### 3.4) Eliminar un departamento

```ts
// Elimina un departamento por id
export const deleteDepartamento = async (id: number): Promise<void> => {
  // Llamamos al endpoint DELETE; si hay error lanzará desde handleApiRequest
  await handleApiRequest<unknown>(`/cat/departamentos/${id}`, {
    method: 'DELETE',
  });

  // Si llegamos aquí la eliminación fue exitosa
};
```

---

### 4) Endpoints de Municipios (`/cat/municipios`)

Del README del proyecto:

- `GET /cat/municipios` (filtros: `?q=`, `?departamento_id=`)
- `POST /cat/municipios`
- `PUT /cat/municipios/{id}`
- `DELETE /cat/municipios/{id}`

JSON esperado:

```json
{
  "departamento_id": 1,
  "nombre": "Cochabamba"
}
```

#### 4.1) Listar municipios por filtro de texto y/o departamento

```ts
// municipiosApi.ts
// -----------------------------------------
// Funciones CRUD para Municipios
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type { Municipio } from './catalogTypes';

// Lista municipios filtrando opcionalmente por texto y/o departamento
export const getMunicipios = async (params?: {
  searchText?: string;
  departamentoId?: number;
}): Promise<Municipio[]> => {
  // Construimos arreglo de pares clave=valor para el query string
  const queryParts: string[] = [];

  if (params?.searchText) {
    queryParts.push(`q=${encodeURIComponent(params.searchText)}`);
  }

  if (params?.departamentoId) {
    queryParts.push(`departamento_id=${encodeURIComponent(
      String(params.departamentoId)
    )}`);
  }

  // Unimos las partes solo si existen filtros
  const query = queryParts.length > 0 ? `?${queryParts.join('&')}` : '';

  // GET al endpoint principal de municipios
  const municipios = await handleApiRequest<Municipio[]>(
    `/cat/municipios${query}`,
    {
      method: 'GET',
    }
  );

  // Devolvemos la lista ya filtrada
  return municipios;
};
```

#### 4.2) Crear un municipio

```ts
// Crea un municipio nuevo ligándolo a un departamento
export const createMunicipio = async (data: {
  departamento_id: number;
  nombre: string;
}): Promise<Municipio> => {
  // POST con el JSON esperado por el backend
  const nuevoMunicipio = await handleApiRequest<Municipio>(
    '/cat/municipios',
    {
      method: 'POST',
      body: JSON.stringify(data),
    }
  );

  // Devolvemos el municipio recién creado
  return nuevoMunicipio;
};
```

#### 4.3) Actualizar un municipio

```ts
// Actualiza un municipio existente
export const updateMunicipio = async (
  id: number,
  data: {
    departamento_id: number;
    nombre: string;
  }
): Promise<Municipio> => {
  // PUT al recurso específico de municipio
  const updatedMunicipio = await handleApiRequest<Municipio>(
    `/cat/municipios/${id}`,
    {
      method: 'PUT',
      body: JSON.stringify(data),
    }
  );

  // Devolvemos el municipio ya actualizado
  return updatedMunicipio;
};
```

#### 4.4) Eliminar un municipio

```ts
// Borra un municipio por su identificador
export const deleteMunicipio = async (id: number): Promise<void> => {
  // Ejecutamos el DELETE y dejamos que los errores se propaguen
  await handleApiRequest<unknown>(`/cat/municipios/${id}`, {
    method: 'DELETE',
  });
};
```

---

### 5) Endpoints de Variedades de papa (`/cat/variedades`)

Del README del proyecto:

- `GET /cat/variedades` (index, filtro `?q=` por código/nombre)
- `POST /cat/variedades`
- `PUT /cat/variedades/{id}`
- `DELETE /cat/variedades/{id}`

JSON esperado:

```json
{
  "codigo_variedad": "WAYCHA",
  "nombre_comercial": "Waych'a",
  "aptitud": "Mesa",
  "ciclo_dias_min": 110,
  "ciclo_dias_max": 140
}
```

#### 5.1) Listar variedades de papa con filtro

```ts
// variedadesApi.ts
// -----------------------------------------
// Funciones CRUD para Variedades de papa
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type { VariedadPapa } from './catalogTypes';

// Lista variedades filtrando por código o nombre (?q=)
export const getVariedades = async (
  searchText?: string
): Promise<VariedadPapa[]> => {
  // Agregamos el query solo si hay texto de búsqueda
  const query = searchText ? `?q=${encodeURIComponent(searchText)}` : '';

  // GET al endpoint principal de variedades
  const variedades = await handleApiRequest<VariedadPapa[]>(
    `/cat/variedades${query}`,
    {
      method: 'GET',
    }
  );

  // Devolvemos las variedades obtenidas
  return variedades;
};
```

#### 5.2) Crear variedad de papa

```ts
// Estructura de datos permitida por el backend al crear una variedad
export type CreateVariedadPayload = {
  codigo_variedad: string;
  nombre_comercial: string;
  aptitud?: string | null;
  ciclo_dias_min?: number | null;
  ciclo_dias_max?: number | null;
};

// Crea una nueva variedad enviando el JSON esperado
export const createVariedad = async (
  payload: CreateVariedadPayload
): Promise<VariedadPapa> => {
  // POST al endpoint de creación de variedades
  const nuevaVariedad = await handleApiRequest<VariedadPapa>(
    '/cat/variedades',
    {
      method: 'POST',
      body: JSON.stringify(payload),
    }
  );

  // Devolvemos la variedad ya registrada
  return nuevaVariedad;
};
```

#### 5.3) Actualizar variedad de papa

```ts
// Reutilizamos el mismo payload de creación para actualizar
export type UpdateVariedadPayload = CreateVariedadPayload;

// Actualiza una variedad existente usando su id
export const updateVariedad = async (
  id: number,
  payload: UpdateVariedadPayload
): Promise<VariedadPapa> => {
  // PUT con el cuerpo JSON actualizado
  const updatedVariedad = await handleApiRequest<VariedadPapa>(
    `/cat/variedades/${id}`,
    {
      method: 'PUT',
      body: JSON.stringify(payload),
    }
  );

  // Devolvemos la variedad ya modificada
  return updatedVariedad;
};
```

#### 5.4) Eliminar variedad de papa

```ts
// Elimina una variedad por identificador
export const deleteVariedad = async (id: number): Promise<void> => {
  // DELETE directo al recurso; errores se manejan vía excepciones
  await handleApiRequest<unknown>(`/cat/variedades/${id}`, {
    method: 'DELETE',
  });
};
```

---

### 6) Ejemplo de pantalla React Native consumiendo `/cat/variedades`

Ejemplo sencillo de pantalla que:

- Lista variedades de papa.
- Permite buscar por código/nombre.
- Muestra mensajes de carga y error.

```tsx
// VariedadesScreen.tsx
// -----------------------------------------
// Pantalla de ejemplo que consume el endpoint /cat/variedades
// -----------------------------------------

import React, { useEffect, useState } from 'react';
import {
  ActivityIndicator,
  FlatList,
  SafeAreaView,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import type { VariedadPapa } from './catalogTypes';
import { getVariedades } from './variedadesApi';

// Componente principal de pantalla de variedades
export const VariedadesScreen: React.FC = () => {
  // Estado local para lista de variedades
  const [variedades, setVariedades] = useState<VariedadPapa[]>([]);
  // Estado para el texto de búsqueda
  const [searchText, setSearchText] = useState<string>('');
  // Estado de carga mientras pedimos datos
  const [isLoading, setIsLoading] = useState<boolean>(false);
  // Estado de mensaje de error, si ocurre
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  // Función que dispara la carga de datos desde el backend
  const handleLoadVariedades = async () => {
    setIsLoading(true);
    setErrorMessage(null);

    try {
      // Llamamos a la API con el filtro actual
      const data = await getVariedades(searchText);
      // Actualizamos estado con los resultados
      setVariedades(data);
    } catch (error) {
      // Si algo falla, mostramos un mensaje genérico
      setErrorMessage('No se pudieron cargar las variedades.');
      console.error('Error cargando variedades:', error);
    } finally {
      // Quitamos el indicador de carga siempre al final
      setIsLoading(false);
    }
  };

  // Cargar al entrar por primera vez en la pantalla
  useEffect(() => {
    handleLoadVariedades();
  }, []);

  // Render simple de cada fila de variedad
  const renderVariedadItem = ({ item }: { item: VariedadPapa }) => {
    return (
      <View
        style={{
          paddingVertical: 8,
          borderBottomWidth: 1,
          borderColor: '#e5e5e5',
        }}
      >
        <Text style={{ fontWeight: '600' }}>
          {item.codigo_variedad} - {item.nombre_comercial}
        </Text>
        {item.aptitud ? (
          <Text style={{ color: '#555' }}>Aptitud: {item.aptitud}</Text>
        ) : null}
        <Text style={{ color: '#777', fontSize: 12 }}>
          Ciclo (días): {item.ciclo_dias_min ?? '-'} -{' '}
          {item.ciclo_dias_max ?? '-'}
        </Text>
      </View>
    );
  };

  // UI principal de la pantalla
  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: '#fff' }}>
      <View style={{ flex: 1, paddingHorizontal: 16, paddingTop: 16 }}>
        {/* Campo de búsqueda */}
        <TextInput
          placeholder="Buscar por código o nombre..."
          value={searchText}
          onChangeText={setSearchText}
          style={{
            borderWidth: 1,
            borderColor: '#000',
            paddingHorizontal: 12,
            paddingVertical: 8,
            marginBottom: 12,
          }}
        />

        {/* Botón para recargar resultados con el filtro actual */}
        <TouchableOpacity
          onPress={handleLoadVariedades}
          style={{
            backgroundColor: '#000',
            paddingVertical: 10,
            alignItems: 'center',
            marginBottom: 12,
          }}
        >
          <Text style={{ color: '#fff', fontWeight: '600' }}>Buscar</Text>
        </TouchableOpacity>

        {/* Indicador de carga */}
        {isLoading ? <ActivityIndicator size="small" color="#000" /> : null}

        {/* Mensaje de error si algo salió mal */}
        {errorMessage ? (
          <Text style={{ color: 'red', marginBottom: 8 }}>{errorMessage}</Text>
        ) : null}

        {/* Lista de variedades */}
        <FlatList
          data={variedades}
          keyExtractor={(item) => String(item.variedad_id)}
          renderItem={renderVariedadItem}
        />
      </View>
    </SafeAreaView>
  );
};
```

---

### 7) Resumen

- Usa `handleApiRequest` como helper central para **todas las llamadas** a `/cat/departamentos`, `/cat/municipios` y `/cat/variedades`.
- Mantén los **tipos TypeScript** (`Departamento`, `Municipio`, `VariedadPapa`) en un archivo común y separa cada grupo de endpoints en su propio módulo (`departamentosApi.ts`, `municipiosApi.ts`, `variedadesApi.ts`).
- Desde tus pantallas de React Native, llama a estas funciones y maneja estados de **carga, error y datos** como en `VariedadesScreen`.

---

### 8) Otros catálogos CRUD `/cat` (plantas, clientes, transportistas, almacenes)

Además de los catálogos ya vistos, el backend expone más recursos CRUD bajo el prefijo `/cat`:

- **Plantas**: `/cat/plantas`
- **Clientes**: `/cat/clientes`
- **Transportistas**: `/cat/transportistas`
- **Almacenes**: `/cat/almacenes`

Todos siguen el mismo patrón:

- `GET /cat/recurso` → listado paginado con filtro `?q=`.
- `POST /cat/recurso` → crear.
- `PUT /cat/recurso/{id}` → actualizar.
- `DELETE /cat/recurso/{id}` → eliminar.

#### 8.1) Tipos TypeScript para estos catálogos

```ts
// catalogTypes.ts (continuación)
// -----------------------------------------
// Tipos para plantas, clientes, transportistas, almacenes
// -----------------------------------------

// Planta de proceso
export type Planta = {
  planta_id: number;
  codigo_planta: string;
  nombre: string;
  municipio_id: number;
  direccion?: string | null;
  lat?: number | null;
  lon?: number | null;
  created_at?: string;
  updated_at?: string;
};

// Cliente (mayorista, supermercado, etc.)
export type Cliente = {
  cliente_id: number;
  codigo_cliente: string;
  nombre: string;
  tipo: string;
  municipio_id?: number | null;
  direccion?: string | null;
  lat?: number | null;
  lon?: number | null;
  created_at?: string;
  updated_at?: string;
};

// Transportista (persona o empresa)
export type Transportista = {
  transportista_id: number;
  codigo_transp: string;
  nombre: string;
  nro_licencia?: string | null;
  created_at?: string;
  updated_at?: string;
};

// Almacén físico
export type Almacen = {
  almacen_id: number;
  codigo_almacen: string;
  nombre: string;
  municipio_id: number;
  direccion?: string | null;
  lat?: number | null;
  lon?: number | null;
  created_at?: string;
  updated_at?: string;
};
```

#### 8.2) CRUD de Plantas (`/cat/plantas`)

```ts
// plantasApi.ts
// -----------------------------------------
// Funciones CRUD para Plantas
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type { Planta } from './catalogTypes';

// Lista plantas con filtro opcional (?q= por código o nombre)
export const getPlantas = async (searchText?: string): Promise<Planta[]> => {
  const query = searchText ? `?q=${encodeURIComponent(searchText)}` : '';

  const plantas = await handleApiRequest<Planta[]>(`/cat/plantas${query}`, {
    method: 'GET',
  });

  return plantas;
};

// Datos mínimos para crear/actualizar planta
export type PlantaPayload = {
  codigo_planta: string;
  nombre: string;
  municipio_id: number;
  direccion?: string | null;
  lat?: number | null;
  lon?: number | null;
};

// Crea una planta
export const createPlanta = async (
  payload: PlantaPayload
): Promise<Planta> => {
  const nuevaPlanta = await handleApiRequest<Planta>('/cat/plantas', {
    method: 'POST',
    body: JSON.stringify(payload),
  });

  return nuevaPlanta;
};

// Actualiza una planta
export const updatePlanta = async (
  plantaId: number,
  payload: PlantaPayload
): Promise<Planta> => {
  const updatedPlanta = await handleApiRequest<Planta>(
    `/cat/plantas/${plantaId}`,
    {
      method: 'PUT',
      body: JSON.stringify(payload),
    }
  );

  return updatedPlanta;
};

// Elimina una planta
export const deletePlanta = async (plantaId: number): Promise<void> => {
  await handleApiRequest<unknown>(`/cat/plantas/${plantaId}`, {
    method: 'DELETE',
  });
};
```

#### 8.3) CRUD de Clientes (`/cat/clientes`)

```ts
// clientesApi.ts
// -----------------------------------------
// Funciones CRUD para Clientes
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type { Cliente } from './catalogTypes';

// Lista clientes filtrando por código, nombre o tipo (?q=)
export const getClientes = async (searchText?: string): Promise<Cliente[]> => {
  const query = searchText ? `?q=${encodeURIComponent(searchText)}` : '';

  const clientes = await handleApiRequest<Cliente[]>(`/cat/clientes${query}`, {
    method: 'GET',
  });

  return clientes;
};

// Datos para crear/actualizar cliente
export type ClientePayload = {
  codigo_cliente: string;
  nombre: string;
  tipo: string;
  municipio_id?: number | null;
  direccion?: string | null;
  lat?: number | null;
  lon?: number | null;
};

// Crea un cliente
export const createCliente = async (
  payload: ClientePayload
): Promise<Cliente> => {
  const nuevoCliente = await handleApiRequest<Cliente>('/cat/clientes', {
    method: 'POST',
    body: JSON.stringify(payload),
  });

  return nuevoCliente;
};

// Actualiza un cliente
export const updateCliente = async (
  clienteId: number,
  payload: ClientePayload
): Promise<Cliente> => {
  const updatedCliente = await handleApiRequest<Cliente>(
    `/cat/clientes/${clienteId}`,
    {
      method: 'PUT',
      body: JSON.stringify(payload),
    }
  );

  return updatedCliente;
};

// Elimina un cliente
export const deleteCliente = async (clienteId: number): Promise<void> => {
  await handleApiRequest<unknown>(`/cat/clientes/${clienteId}`, {
    method: 'DELETE',
  });
};
```

#### 8.4) CRUD de Transportistas (`/cat/transportistas`)

```ts
// transportistasApi.ts
// -----------------------------------------
// Funciones CRUD para Transportistas
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type { Transportista } from './catalogTypes';

// Lista transportistas filtrando por código o nombre (?q=)
export const getTransportistas = async (
  searchText?: string
): Promise<Transportista[]> => {
  const query = searchText ? `?q=${encodeURIComponent(searchText)}` : '';

  const transportistas = await handleApiRequest<Transportista[]>(
    `/cat/transportistas${query}`,
    {
      method: 'GET',
    }
  );

  return transportistas;
};

// Datos para crear/actualizar transportista
export type TransportistaPayload = {
  codigo_transp: string;
  nombre: string;
  nro_licencia?: string | null;
};

// Crea transportista
export const createTransportista = async (
  payload: TransportistaPayload
): Promise<Transportista> => {
  const nuevoTransportista = await handleApiRequest<Transportista>(
    '/cat/transportistas',
    {
      method: 'POST',
      body: JSON.stringify(payload),
    }
  );

  return nuevoTransportista;
};

// Actualiza transportista
export const updateTransportista = async (
  transportistaId: number,
  payload: TransportistaPayload
): Promise<Transportista> => {
  const updatedTransportista = await handleApiRequest<Transportista>(
    `/cat/transportistas/${transportistaId}`,
    {
      method: 'PUT',
      body: JSON.stringify(payload),
    }
  );

  return updatedTransportista;
};

// Elimina transportista
export const deleteTransportista = async (
  transportistaId: number
): Promise<void> => {
  await handleApiRequest<unknown>(`/cat/transportistas/${transportistaId}`, {
    method: 'DELETE',
  });
};
```

#### 8.5) CRUD de Almacenes (`/cat/almacenes`)

```ts
// almacenesApi.ts
// -----------------------------------------
// Funciones CRUD para Almacenes
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type { Almacen } from './catalogTypes';

// Lista almacenes filtrando por código o nombre (?q=)
export const getAlmacenes = async (
  searchText?: string
): Promise<Almacen[]> => {
  const query = searchText ? `?q=${encodeURIComponent(searchText)}` : '';

  const almacenes = await handleApiRequest<Almacen[]>(
    `/cat/almacenes${query}`,
    {
      method: 'GET',
    }
  );

  return almacenes;
};

// Datos para crear/actualizar almacén
export type AlmacenPayload = {
  codigo_almacen: string;
  nombre: string;
  municipio_id: number;
  direccion?: string | null;
  lat?: number | null;
  lon?: number | null;
};

// Crea almacén
export const createAlmacen = async (
  payload: AlmacenPayload
): Promise<Almacen> => {
  const nuevoAlmacen = await handleApiRequest<Almacen>('/cat/almacenes', {
    method: 'POST',
    body: JSON.stringify(payload),
  });

  return nuevoAlmacen;
};

// Actualiza almacén
export const updateAlmacen = async (
  almacenId: number,
  payload: AlmacenPayload
): Promise<Almacen> => {
  const updatedAlmacen = await handleApiRequest<Almacen>(
    `/cat/almacenes/${almacenId}`,
    {
      method: 'PUT',
      body: JSON.stringify(payload),
    }
  );

  return updatedAlmacen;
};

// Elimina almacén
export const deleteAlmacen = async (almacenId: number): Promise<void> => {
  await handleApiRequest<unknown>(`/cat/almacenes/${almacenId}`, {
    method: 'DELETE',
  });
};
```

---

### 9) Catálogos CRUD `/campo` (productores, lotes de campo, lecturas de sensor)

El prefijo `/campo` agrupa recursos orientados al **campo agrícola**:

- Productores: `/campo/productores`
- Lotes de campo: `/campo/lotes`
- Lecturas de sensor: `/campo/lecturas`

Todos exponen rutas REST:

- `GET /campo/recurso` → listado (algunos con filtros por query string).
- `POST /campo/recurso` → crear.
- `PUT /campo/recurso/{id}` → actualizar.
- `DELETE /campo/recurso/{id}` → eliminar.

#### 9.1) Tipos TypeScript para `/campo`

```ts
// campoTypes.ts
// -----------------------------------------
// Tipos para recursos del módulo Campo
// -----------------------------------------

// Productor agrícola
export type Productor = {
  productor_id: number;
  codigo_productor: string;
  nombre: string;
  municipio_id: number;
  telefono?: string | null;
  created_at?: string;
  updated_at?: string;
};

// Lote de campo (siembra/cosecha)
export type LoteCampo = {
  lote_campo_id: number;
  codigo_lote_campo: string;
  productor_id: number;
  variedad_id: number;
  superficie_ha: number;
  fecha_siembra: string;
  fecha_cosecha?: string | null;
  humedad_suelo_pct?: number | null;
  created_at?: string;
  updated_at?: string;
};

// Lectura de sensor asociada a un lote
export type SensorLectura = {
  sensor_lectura_id: number;
  lote_campo_id: number;
  fecha_hora: string;
  tipo: string;
  valor_num?: number | null;
  valor_texto?: string | null;
  created_at?: string;
  updated_at?: string;
};
```

#### 9.2) CRUD de Productores (`/campo/productores`)

```ts
// productoresApi.ts
// -----------------------------------------
// Funciones CRUD para Productores
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type { Productor } from './campoTypes';

// Lista productores con filtro por código o nombre (?q=)
export const getProductores = async (
  searchText?: string
): Promise<Productor[]> => {
  const query = searchText ? `?q=${encodeURIComponent(searchText)}` : '';

  const productores = await handleApiRequest<Productor[]>(
    `/campo/productores${query}`,
    {
      method: 'GET',
    }
  );

  return productores;
};

// Datos para crear/actualizar productor
export type ProductorPayload = {
  codigo_productor: string;
  nombre: string;
  municipio_id: number;
  telefono?: string | null;
};

// Crea productor
export const createProductor = async (
  payload: ProductorPayload
): Promise<Productor> => {
  const nuevoProductor = await handleApiRequest<Productor>(
    '/campo/productores',
    {
      method: 'POST',
      body: JSON.stringify(payload),
    }
  );

  return nuevoProductor;
};

// Actualiza productor
export const updateProductor = async (
  productorId: number,
  payload: ProductorPayload
): Promise<Productor> => {
  const updatedProductor = await handleApiRequest<Productor>(
    `/campo/productores/${productorId}`,
    {
      method: 'PUT',
      body: JSON.stringify(payload),
    }
  );

  return updatedProductor;
};

// Elimina productor
export const deleteProductor = async (productorId: number): Promise<void> => {
  await handleApiRequest<unknown>(`/campo/productores/${productorId}`, {
    method: 'DELETE',
  });
};
```

#### 9.3) CRUD de Lotes de campo (`/campo/lotes`)

```ts
// lotesCampoApi.ts
// -----------------------------------------
// Funciones CRUD para Lotes de campo
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type { LoteCampo } from './campoTypes';

// Lista lotes filtrando por código de lote (?q=)
export const getLotesCampo = async (
  searchText?: string
): Promise<LoteCampo[]> => {
  const query = searchText ? `?q=${encodeURIComponent(searchText)}` : '';

  const lotes = await handleApiRequest<LoteCampo[]>(`/campo/lotes${query}`, {
    method: 'GET',
  });

  return lotes;
};

// Datos para crear/actualizar lote de campo
export type LoteCampoPayload = {
  codigo_lote_campo: string;
  productor_id: number;
  variedad_id: number;
  superficie_ha: number;
  fecha_siembra: string;
  fecha_cosecha?: string | null;
  humedad_suelo_pct?: number | null;
};

// Crea lote de campo
export const createLoteCampo = async (
  payload: LoteCampoPayload
): Promise<LoteCampo> => {
  const nuevoLote = await handleApiRequest<LoteCampo>('/campo/lotes', {
    method: 'POST',
    body: JSON.stringify(payload),
  });

  return nuevoLote;
};

// Actualiza lote de campo
export const updateLoteCampo = async (
  loteId: number,
  payload: LoteCampoPayload
): Promise<LoteCampo> => {
  const updatedLote = await handleApiRequest<LoteCampo>(
    `/campo/lotes/${loteId}`,
    {
      method: 'PUT',
      body: JSON.stringify(payload),
    }
  );

  return updatedLote;
};

// Elimina lote de campo
export const deleteLoteCampo = async (loteId: number): Promise<void> => {
  await handleApiRequest<unknown>(`/campo/lotes/${loteId}`, {
    method: 'DELETE',
  });
};
```

#### 9.4) CRUD de Lecturas de sensor (`/campo/lecturas`)

```ts
// lecturasApi.ts
// -----------------------------------------
// Funciones CRUD y filtros para lecturas de sensor
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type { SensorLectura } from './campoTypes';

// Lista lecturas con filtros opcionales por lote, tipo y rango de fechas
export const getLecturas = async (params?: {
  lote_campo_id?: number;
  tipo?: string;
  desde?: string;
  hasta?: string;
}): Promise<SensorLectura[]> => {
  const queryParts: string[] = [];

  if (params?.lote_campo_id) {
    queryParts.push(`lote_campo_id=${encodeURIComponent(
      String(params.lote_campo_id)
    )}`);
  }

  if (params?.tipo) {
    queryParts.push(`tipo=${encodeURIComponent(params.tipo)}`);
  }

  if (params?.desde) {
    queryParts.push(`desde=${encodeURIComponent(params.desde)}`);
  }

  if (params?.hasta) {
    queryParts.push(`hasta=${encodeURIComponent(params.hasta)}`);
  }

  const query = queryParts.length > 0 ? `?${queryParts.join('&')}` : '';

  const lecturas = await handleApiRequest<SensorLectura[]>(
    `/campo/lecturas${query}`,
    {
      method: 'GET',
    }
  );

  return lecturas;
};

// Datos para crear/actualizar lectura
export type SensorLecturaPayload = {
  lote_campo_id: number;
  fecha_hora: string;
  tipo: string;
  valor_num?: number | null;
  valor_texto?: string | null;
};

// Crea lectura de sensor
export const createLectura = async (
  payload: SensorLecturaPayload
): Promise<SensorLectura> => {
  const nuevaLectura = await handleApiRequest<SensorLectura>(
    '/campo/lecturas',
    {
      method: 'POST',
      body: JSON.stringify(payload),
    }
  );

  return nuevaLectura;
};

// Actualiza lectura de sensor
export const updateLectura = async (
  lecturaId: number,
  payload: SensorLecturaPayload
): Promise<SensorLectura> => {
  const updatedLectura = await handleApiRequest<SensorLectura>(
    `/campo/lecturas/${lecturaId}`,
    {
      method: 'PUT',
      body: JSON.stringify(payload),
    }
  );

  return updatedLectura;
};

// Elimina lectura de sensor
export const deleteLectura = async (lecturaId: number): Promise<void> => {
  await handleApiRequest<unknown>(`/campo/lecturas/${lecturaId}`, {
    method: 'DELETE',
  });
};
```

---

### 10) Transacciones de Planta (`/tx/planta`): lotes de planta y lotes de salida

Estas rutas ejecutan **funciones almacenadas** en PostgreSQL (esquema `planta`) y están bajo el prefijo `/tx/planta`:

- `POST /tx/planta/lote-planta` → registra un lote de planta (`planta.sp_registrar_lote_planta`).
- `POST /tx/planta/lote-salida-envio` → registra un lote de salida y opcionalmente un envío (`planta.sp_registrar_lote_salida_y_envio`).

> Nota: también existen rutas `GET` con formularios Blade, pero desde React Native usarás sólo los `POST` con JSON.

#### 10.1) Tipos y payload para `POST /tx/planta/lote-planta`

```ts
// txPlantaTypes.ts
// -----------------------------------------
// Tipos para transacciones de planta
// -----------------------------------------

// Entrada de materia prima para un lote de planta
export type EntradaLotePlanta = {
  lote_campo_id: number;
  peso_entrada_t: number;
};

// Payload para registrar lote de planta
export type RegistrarLotePlantaPayload = {
  codigo_lote_planta: string;
  planta_id: number;
  fecha_inicio: string;
  entradas: EntradaLotePlanta[];
};

// Respuesta típica del backend al registrar lote de planta
export type RegistrarLotePlantaResponse = {
  status: 'ok';
  message: string;
  data: {
    codigo_lote_planta: string;
    planta_id: number;
    entradas_count: number;
  };
};
```

```ts
// txPlantaApi.ts (parte 1)
// -----------------------------------------
// Llamada a /tx/planta/lote-planta
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type {
  RegistrarLotePlantaPayload,
  RegistrarLotePlantaResponse,
} from './txPlantaTypes';

// Registra un lote de planta (usa sp_registrar_lote_planta)
export const registrarLotePlanta = async (
  payload: RegistrarLotePlantaPayload
): Promise<RegistrarLotePlantaResponse> => {
  const response = await handleApiRequest<RegistrarLotePlantaResponse>(
    '/tx/planta/lote-planta',
    {
      method: 'POST',
      body: JSON.stringify(payload),
    }
  );

  return response;
};
```

#### 10.2) Tipos y payload para `POST /tx/planta/lote-salida-envio`

```ts
// txPlantaTypes.ts (continuación)
// -----------------------------------------

// Payload base para registrar lote de salida
export type RegistrarLoteSalidaPayloadBase = {
  codigo_lote_salida: string;
  lote_planta_id: number;
  sku: string;
  peso_t: number;
  fecha_empaque: string;
};

// Campos adicionales si también se crea un envío
export type RegistrarEnvioOpcionalPayload = {
  crear_envio?: boolean;
  codigo_envio?: string;
  ruta_id?: number | null;
  transportista_id?: number | null;
  fecha_salida?: string | null;
};

// Payload completo que acepta ambos escenarios
export type RegistrarLoteSalidaEnvioPayload =
  RegistrarLoteSalidaPayloadBase & RegistrarEnvioOpcionalPayload;

// Respuesta al registrar lote de salida (con o sin envío)
export type RegistrarLoteSalidaEnvioResponse = {
  status: 'ok';
  message: string;
  data: {
    codigo_lote_salida: string;
    codigo_envio?: string | null;
    crear_envio: boolean;
  };
};
```

```ts
// txPlantaApi.ts (parte 2)
// -----------------------------------------
// Llamada a /tx/planta/lote-salida-envio
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type {
  RegistrarLoteSalidaEnvioPayload,
  RegistrarLoteSalidaEnvioResponse,
} from './txPlantaTypes';

// Registra lote de salida y opcionalmente un envío
export const registrarLoteSalidaEnvio = async (
  payload: RegistrarLoteSalidaEnvioPayload
): Promise<RegistrarLoteSalidaEnvioResponse> => {
  const response = await handleApiRequest<RegistrarLoteSalidaEnvioResponse>(
    '/tx/planta/lote-salida-envio',
    {
      method: 'POST',
      body: JSON.stringify(payload),
    }
  );

  return response;
};
```

---

### 11) Transacciones de Almacén (`/tx/almacen`): despachos y recepciones

El módulo de almacén expone operaciones transaccionales que ejecutan funciones del esquema `almacen` en PostgreSQL:

- `POST /tx/almacen/despachar-al-almacen` → `almacen.sp_despachar_a_almacen`.
- `POST /tx/almacen/recepcionar-envio` → `almacen.sp_recepcionar_envio`.
- `POST /tx/almacen/despachar-al-cliente` → `almacen.sp_despachar_a_cliente`.

Estas rutas aceptan JSON y devuelven JSON cuando el request incluye `Accept: application/json`.

#### 11.1) Tipos y payload para `POST /tx/almacen/despachar-al-almacen`

```ts
// txAlmacenTypes.ts
// -----------------------------------------
// Tipos para transacciones de almacén
// -----------------------------------------

// Detalle de cada lote despacho hacia almacén
export type DespacharAlmacenDetalleItem = {
  codigo_lote_salida: string;
  cantidad_t: number;
};

// Payload para sp_despachar_a_almacen
export type DespacharAlmacenPayload = {
  codigo_envio: string;
  transportista_id: number;
  almacen_destino_id: number;
  fecha_salida: string;
  detalle: DespacharAlmacenDetalleItem[];
};

// Respuesta de envío a almacén
export type DespacharAlmacenResponse = {
  status: 'ok';
  message: string;
  data: {
    codigo_envio: string;
  };
};
```

```ts
// txAlmacenApi.ts (parte 1)
// -----------------------------------------
// Llamada a /tx/almacen/despachar-al-almacen
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type {
  DespacharAlmacenPayload,
  DespacharAlmacenResponse,
} from './txAlmacenTypes';

// Registra un envío desde planta hacia un almacén
export const despacharAlmacen = async (
  payload: DespacharAlmacenPayload
): Promise<DespacharAlmacenResponse> => {
  const response = await handleApiRequest<DespacharAlmacenResponse>(
    '/tx/almacen/despachar-al-almacen',
    {
      method: 'POST',
      body: JSON.stringify(payload),
    }
  );

  return response;
};
```

#### 11.2) Tipos y payload para `POST /tx/almacen/recepcionar-envio`

```ts
// txAlmacenTypes.ts (continuación)
// -----------------------------------------

// Payload para recepcionar un envío en almacén
export type RecepcionarEnvioPayload = {
  codigo_envio: string;
  almacen_id: number;
  observacion?: string | null;
};

// Respuesta exitosa
export type RecepcionarEnvioResponse = {
  status: 'ok';
  message: string;
};

// Respuesta de error cuando el envío no existe
export type RecepcionarEnvioErrorResponse = {
  status: 'error';
  message: string;
};
```

```ts
// txAlmacenApi.ts (parte 2)
// -----------------------------------------
// Llamada a /tx/almacen/recepcionar-envio
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type {
  RecepcionarEnvioPayload,
  RecepcionarEnvioResponse,
} from './txAlmacenTypes';

// Recepciona un envío y actualiza stock en el almacén
export const recepcionarEnvio = async (
  payload: RecepcionarEnvioPayload
): Promise<RecepcionarEnvioResponse> => {
  const response = await handleApiRequest<RecepcionarEnvioResponse>(
    '/tx/almacen/recepcionar-envio',
    {
      method: 'POST',
      body: JSON.stringify(payload),
    }
  );

  return response;
};
```

> Si el backend devuelve HTTP 404 con `status: 'error'`, `handleApiRequest` lanzará una excepción.  
> Puedes capturarla en la pantalla React Native para mostrar un mensaje tipo: “No se encontró el envío especificado”.

#### 11.3) Tipos y payload para `POST /tx/almacen/despachar-al-cliente`

```ts
// txAlmacenTypes.ts (continuación)
// -----------------------------------------

// Detalle de cada lote en despacho a cliente
export type DespacharClienteDetalleItem = {
  codigo_lote_salida: string;
  cantidad_t: number;
};

// Payload para sp_despachar_a_cliente
export type DespacharClientePayload = {
  codigo_envio: string;
  almacen_origen_id: number;
  cliente_id: number;
  transportista_id: number;
  fecha_salida: string;
  detalle: DespacharClienteDetalleItem[];
};

// Respuesta de despacho a cliente
export type DespacharClienteResponse = {
  status: 'ok';
  message: string;
  data: {
    codigo_envio: string;
  };
};
```

```ts
// txAlmacenApi.ts (parte 3)
// -----------------------------------------
// Llamada a /tx/almacen/despachar-al-cliente
// -----------------------------------------

import { handleApiRequest } from './apiClient';
import type {
  DespacharClientePayload,
  DespacharClienteResponse,
} from './txAlmacenTypes';

// Registra un despacho desde almacén hacia un cliente
export const despacharCliente = async (
  payload: DespacharClientePayload
): Promise<DespacharClienteResponse> => {
  const response = await handleApiRequest<DespacharClienteResponse>(
    '/tx/almacen/despachar-al-cliente',
    {
      method: 'POST',
      body: JSON.stringify(payload),
    }
  );

  return response;
};
```

---

### 12) Cómo encajar todos los módulos en tu app React Native

- **Catálogos (`/cat`, `/campo`)**:
  - Usa módulos tipo `departamentosApi.ts`, `clientesApi.ts`, `productoresApi.ts` para CRUDs simples.
  - En las pantallas, combina estos endpoints con selects dependientes (por ejemplo, seleccionar `Departamento` → cargar `Municipios`).
- **Transaccionales (`/tx/planta`, `/tx/almacen`)**:
  - Crea pantallas tipo “wizard” donde primero eliges origen/destino y luego armas el array `detalle` o `entradas`.
  - Envía siempre JSON con la estructura mostrada en los payloads y maneja errores de negocio capturando excepciones de `handleApiRequest`.

