# Portabilidad de Base de Datos (PostgreSQL)

Este proyecto (web Laravel + app movil Flutter) consume la misma base `app_pollos_el_dorado`.

## Opcion A (recomendada): Migraciones Laravel

En una nueva PC:

1. Crear la base:

```sql
CREATE DATABASE app_pollos_el_dorado;
```

2. Configurar `backend/api/.env` con:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=app_pollos_el_dorado
DB_USERNAME=postgres
DB_PASSWORD=TU_PASSWORD
```

3. Ejecutar:

```powershell
cd C:\Proyectos\AppPollos\backend\api
php artisan migrate
php artisan db:seed
```

## Opcion B: SQL directo (sin artisan)

1. Crear la base:

```sql
CREATE DATABASE app_pollos_el_dorado;
```

2. Conectarte a `app_pollos_el_dorado` y ejecutar en orden:

```sql
\i backend/database/postgresql/schema_full_app_pollos_el_dorado.sql
\i backend/database/postgresql/seed_minimal_app_pollos_el_dorado.sql
```

> En pgAdmin, abre cada archivo y ejecutalo manualmente.

## Exportar tu DB actual para otra PC (con datos reales)

En la PC origen:

```powershell
pg_dump -h 127.0.0.1 -p 5432 -U postgres -F c -b -v -f C:\Backups\app_pollos_el_dorado.backup app_pollos_el_dorado
```

En la PC destino:

```powershell
createdb -h 127.0.0.1 -p 5432 -U postgres app_pollos_el_dorado
pg_restore -h 127.0.0.1 -p 5432 -U postgres -d app_pollos_el_dorado -v C:\Backups\app_pollos_el_dorado.backup
```

## Verificacion rapida

```sql
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM products;
SELECT COUNT(*) FROM orders;
SELECT COUNT(*) FROM order_items;
```

## Nota para Flutter

La app movil consume API, no PostgreSQL directo.  
Solo necesitas que Laravel apunte a esta base y este corriendo en `127.0.0.1:8000`.
