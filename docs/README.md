# CentralHub - Laravel + AdminLTE 3

Sistema de gestión empresarial desarrollado con Laravel y AdminLTE 3.

---

## Requisitos del Sistema

- **PHP** 8.2+ (probado con 8.4) con extensiones: `PDO`, `pdo_pgsql`, `pgsql`
- **Composer** 2.6+
- **Node.js** 18+ (recomendado 20/22) y npm
- **PostgreSQL** 12+

---

## Instalación Rápida

### 1. Clonar e instalar dependencias

```bash
# Instalar dependencias PHP
composer install

# Instalar dependencias frontend (AdminLTE 3, Bootstrap 4, jQuery, FA)
npm install
```

### 2. Configurar entorno

```bash
# Copiar archivo de entorno
copy .env.example .env          # Windows CMD
# Copy-Item .env.example .env   # Windows PowerShell
# cp .env.example .env          # Linux/Mac

# Generar clave de aplicación
php artisan key:generate
```

### 3. Configurar base de datos

Editar `.env` con los datos de PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=centralhub
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# Recomendado para desarrollo
CACHE_STORE=file
```

> **Nota Windows**: Habilitar extensiones en `php.ini`:
> ```ini
> extension=pdo_pgsql
> extension=pgsql
> ```

### 4. Ejecutar migraciones y seeders

```bash
# Limpiar caché primero
php artisan optimize:clear

# Ejecutar migraciones
php artisan migrate

# Poblar datos iniciales (usuarios, roles, datos demo)
php artisan db:seed
```

### 5. Compilar assets

```bash
# Producción
npm run build

# Desarrollo (con hot reload)
npm run dev
```

### 6. Iniciar servidor

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Acceder a: **http://127.0.0.1:8000**

---

## Credenciales por Defecto

| Usuario | Contraseña | Rol |
|---------|------------|-----|
| admin@centralhub.com | password123 | admin |

---

## Estructura de Módulos

### Catálogos (`/cat`)
- Departamentos, Municipios
- Variedades de Papa
- Plantas, Clientes, Transportistas
- Almacenes, Vehículos

### Campo (`/campo`)
- Productores
- Lotes de Campo
- Sensores y Lecturas
- Solicitudes de Producción

### Planta (`/planta`)
- Transacciones de Planta
- Control de Procesos

### Logística (`/logistica`)
- Órdenes de Envío
- Rutas y Transportistas

### Almacén (`/almacen`)
- Dashboard de Almacén
- Recepciones
- Inventario

### Comercial (`/comercial`)
- Pedidos

### Certificación (`/certificaciones`)
- Gestión de Certificados

---

## Roles y Permisos

| Rol | Permisos |
|-----|----------|
| **admin** | Todos los permisos |
| **planta** | Crear solicitudes, gestionar planta, ver trazabilidad |
| **productor** | Responder solicitudes, gestionar campo, ver trazabilidad |
| **conductor** | Ver asignaciones |

---

## API Endpoints (Ejemplo: Departamentos)

```bash
# Listar (con filtro opcional)
GET /cat/departamentos?q=busqueda

# Crear
POST /cat/departamentos
Content-Type: application/json
{ "nombre": "Nombre Departamento" }

# Actualizar
PUT /cat/departamentos/{id}
{ "nombre": "Nuevo Nombre" }

# Eliminar
DELETE /cat/departamentos/{id}
```

---

## Solución de Problemas

| Problema | Solución |
|----------|----------|
| Error conexión PostgreSQL | Verificar usuario/host/puerto en `.env` |
| Error caché/DB circular | Usar `CACHE_STORE=file` en `.env` |
| esbuild no encontrado (Windows) | `npm install esbuild --no-save` |
| Errores de permisos | Ejecutar migraciones con `--seed` reset: `php artisan migrate:fresh --seed` |

---

## Comandos Útiles

```bash
# Resetear BD completa con datos demo
php artisan migrate:fresh --seed

# Solo seeders
php artisan db:seed

# Limpiar todas las cachés
php artisan optimize:clear

# Ver rutas disponibles
php artisan route:list
```
