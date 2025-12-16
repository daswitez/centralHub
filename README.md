Perfecto. Aquí tienes un **README.md COMPLETO, UNIFICADO y LISTO PARA ENTREGA / REPO**, incorporando **TODO**:

* Laravel + Docker + Nginx + PostgreSQL
* Networks internas + Proxy (Nginx Proxy Manager)
* Variables de entorno reales
* APIs externas (Trazabilidad, OrgTrack)
* Comandos reales de despliegue
* Errores comunes
* Buen nivel académico y profesional

Puedes **copiar y pegar tal cual** 👇

---

# 📘 CentralHub – Documentación Completa de Despliegue

**Laravel + Docker + Nginx + PostgreSQL + Nginx Proxy Manager**

Este documento describe **cómo desplegar, configurar y mantener** el proyecto **CentralHub**, un sistema desarrollado en **Laravel**, ejecutado sobre **Docker**, con **Nginx**, **PostgreSQL** y expuesto mediante **Nginx Proxy Manager**.

Está pensado para:

* Replicar el entorno desde cero
* Entrega universitaria
* Onboarding de nuevos integrantes
* Despliegue en servidor

---

## 📑 Índice

1. Arquitectura General
2. Requisitos Previos
3. Estructura del Proyecto
4. Servicios Docker del Proyecto
5. Variables de Entorno
6. Redes Docker (Internas y Proxy)
7. Despliegue Paso a Paso
8. Laravel – Preparación Inicial
9. Base de Datos (PostgreSQL)
10. Nginx y Proxy Reverso
11. APIs Externas Integradas
12. Comandos Útiles
13. Backup y Restore de la Base de Datos
14. Errores Comunes y Soluciones
15. Estado Final Esperado

---

## 🏗️ 1. Arquitectura General

```
Internet
   │
   ▼
Nginx Proxy Manager (80 / 443 / 81)
   │
   ▼
Nginx (contenedor central)
   │
   ▼
Laravel (PHP-FPM)
   │
   ▼
PostgreSQL
```

El sistema está preparado para convivir con **otros proyectos Docker** usando redes compartidas y proxy inverso.

---

## 📦 2. Requisitos Previos

Antes de iniciar, el servidor o máquina local debe tener:

* Docker
* Docker Compose
* Git

Verificación rápida:

```bash
docker --version
docker compose version
git --version
```

---

## 📁 3. Estructura del Proyecto

Archivos clave en la raíz del proyecto:

```
.
├── docker-compose.yml
├── Dockerfile
├── entrypoint.sh
├── nginx.conf
├── .env.example
├── app/
├── database/
└── README.md
```

Estos **4 archivos son obligatorios** para el despliegue correcto:

* `docker-compose.yml`
* `Dockerfile`
* `entrypoint.sh`
* `nginx.conf`

---

## 🐳 4. Servicios Docker del Proyecto

Configuración completa usada en producción y pruebas:

```yaml
services:
  laravel:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: central-laravel
    volumes:
      - .:/var/www
    environment:
      DB_CONNECTION: pgsql
      DB_HOST: db
      DB_PORT: 5432
      DB_DATABASE: central_db
      DB_USERNAME: admin
      DB_PASSWORD: admin123
      TRAZABILIDAD_API_URL: http://trazabilidad.dasalas.shop/api
      ORGTRACK_API_URL: https://orgtrack.dasalas.shop/api
    depends_on:
      - db
    networks:
      - central-net

  nginx:
    image: nginx:latest
    container_name: central
    expose:
      - "80"
    volumes:
      - .:/var/www
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - laravel
    networks:
      - central-net
      - internal-network
      - proxy-network

  db:
    image: postgres:latest
    container_name: central-db
    restart: unless-stopped
    environment:
      POSTGRES_DB: central_db
      POSTGRES_USER: admin
      POSTGRES_PASSWORD: admin123
    volumes:
      - db-data:/var/lib/postgresql
    networks:
      - central-net

networks:
  central-net:
    driver: bridge
  internal-network:
    external: true
  proxy-network:
    external: true

volumes:
  db-data:
```

---

## ⚙️ 5. Variables de Entorno

Archivo base:

```bash
cp .env.example .env
```

Variables importantes:

```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=central_db
DB_USERNAME=admin
DB_PASSWORD=admin123

TRAZABILIDAD_API_URL=http://trazabilidad.dasalas.shop/api
ORGTRACK_API_URL=https://orgtrack.dasalas.shop/api
```

Las variables definidas en `docker-compose.yml` **sobrescriben** las del `.env`.

---

## 🌐 6. Redes Docker (Obligatorio)

Este proyecto utiliza **redes externas compartidas** para proxy y comunicación entre servicios.

### Crear redes (solo una vez por servidor)

```bash
docker network create --driver bridge internal-network
docker network create --driver bridge proxy-network
```

Estas redes **no se eliminan al bajar el proyecto** y se reutilizan para otros sistemas.

---

## 🚀 7. Despliegue Paso a Paso

### 1. Clonar el repositorio

```bash
git clone https://github.com/usuario/centralhub.git
cd centralhub
```

### 2. Levantar contenedores

```bash
docker compose up -d --build
```

### 3. Verificar contenedores

```bash
docker ps
```

---

## 🧠 8. Laravel – Preparación Inicial

Entrar al contenedor:

```bash
docker compose exec laravel bash
```

Ejecutar dentro:

```bash
php artisan key:generate
php artisan optimize:clear
```

---

## 🗄️ 9. Base de Datos (PostgreSQL)

### Migraciones

```bash
php artisan migrate
```

Desde cero (borra todo):

```bash
php artisan migrate:fresh --seed
```

### Acceso directo a PostgreSQL

```bash
docker compose exec db bash
psql -U admin -d central_db
```

Comandos útiles:

```sql
\dt
\q
```

---

## 🌐 10. Nginx y Proxy Reverso

El contenedor `nginx` **no expone puertos directamente**.
El acceso externo se realiza mediante **Nginx Proxy Manager**.

Ejemplo de puertos del proxy:

* 80 → HTTP
* 443 → HTTPS
* 81 → Panel administrativo

---

## 🧩 11. APIs Externas Integradas

El proyecto consume servicios externos:

* **Trazabilidad API**

  ```
  http://trazabilidad.dasalas.shop/api
  ```

* **OrgTrack API**

  ```
  https://orgtrack.dasalas.shop/api
  ```

Estas URLs se configuran por variables de entorno.

---

## 🧪 12. Comandos Útiles

Logs generales:

```bash
docker compose logs -f
```

Logs de Laravel:

```bash
docker logs central-laravel -f
```

Ver rama actual:

```bash
git branch
```

Descartar cambios locales:

```bash
git checkout -- archivo
```

---

## 💾 13. Backup y Restore de Base de Datos

### Exportar

```bash
docker compose exec db pg_dump -U admin central_db > backup.sql
```

### Importar

```bash
docker compose exec -T db psql -U admin central_db < backup.sql
```

---

## 🧯 14. Errores Comunes y Soluciones

### uuid_generate_v4 no existe

```sql
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
```

### geography no existe

```sql
CREATE EXTENSION postgis;
```

### Vendor bloquea el contenedor

```bash
rm -rf vendor
docker compose up -d --build
```

### Cookies / XSRF no se guardan

Revisar:

* `APP_URL`
* `SESSION_DOMAIN`
* HTTPS activo
* Proxy correctamente configurado

---

## ✅ 15. Estado Final Esperado

* Contenedores activos
* Laravel accesible vía dominio
* Base de datos migrada
* APIs externas conectadas
* Proxy funcionando con HTTPS

---

## 📌 Nota Final

Este documento refleja **la configuración real usada en el proyecto**, no una plantilla genérica.

Si algo falla:

1. Revisar logs
2. Verificar redes
3. Confirmar variables
4. Reintentar con `--build`