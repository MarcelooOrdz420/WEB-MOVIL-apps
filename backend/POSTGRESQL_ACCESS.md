# PostgreSQL

## Donde esta la base de datos en el proyecto

En el proyecto tienes estos puntos relacionados:

- Script SQL inicial: `backend/database/postgresql/create_database.sql`
- Migraciones reales de Laravel: `backend/api/database/migrations`
- Seeders reales: `backend/api/database/seeders`
- Configuracion de conexion: `backend/api/.env`
- Configuracion del driver: `backend/api/config/database.php`

## Importante

La base de datos PostgreSQL no se guarda como archivo dentro del proyecto.
Los datos reales viven dentro del servidor PostgreSQL que tienes instalado en tu PC.

Este proyecto solo guarda:
- scripts
- migraciones
- configuracion

## Como abrirla en pgAdmin

1. Abre `pgAdmin 4`
2. Conectate al servidor local:
   - Host: `127.0.0.1`
   - Port: `5432`
   - User: `postgres`
   - Password: la que tengas configurada
3. Busca la base:
   - `app_pollos_el_dorado`
4. Abre:
   - `Schemas`
   - `public`
   - `Tables`

## Como abrirla por consola con psql

1. Abre PowerShell
2. Ejecuta:

```powershell
psql -U postgres -d app_pollos_el_dorado
```

3. Comandos utiles:

```sql
\dt
\d users
\d products
SELECT * FROM products;
SELECT * FROM orders;
```

## Como crear la base

En `pgAdmin`:

1. Abre una consulta conectado a la base `postgres`
2. Ejecuta:

```sql
CREATE DATABASE app_pollos_el_dorado;
```

3. Luego abre una nueva consulta conectado a `app_pollos_el_dorado`
4. Ejecuta:

```sql
CREATE EXTENSION IF NOT EXISTS "pgcrypto";
```

En `psql` por consola:

```powershell
psql -U postgres -f backend/database/postgresql/create_database.sql
```

## Como llenar tablas

Desde `backend/api`:

```powershell
php artisan migrate
php artisan db:seed
```

## Como saber si Laravel esta apuntando a PostgreSQL

Abre `backend/api/.env` y verifica:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=app_pollos_el_dorado
DB_USERNAME=postgres
DB_PASSWORD=postgres
```
