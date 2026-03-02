# Database PostgreSQL

## Motor recomendado
El proyecto queda preparado para usar PostgreSQL con Laravel en `backend/api`.

## Archivos relacionados
- `backend/api/config/database.php`: soporta `pgsql`.
- `backend/api/.env`: conexion real del backend.
- `backend/api/database/migrations`: crea todas las tablas del sistema.
- `backend/database/postgresql/create_database.sql`: script base para crear la base.

## Configuracion esperada
- `DB_CONNECTION=pgsql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=5432`
- `DB_DATABASE=app_pollos_el_dorado`
- `DB_USERNAME=postgres`
- `DB_PASSWORD=postgres`

## Flujo recomendado
1. Crear la base con el script SQL.
2. Copiar esos valores en `backend/api/.env`.
3. Desde `backend/api`, ejecutar `php artisan migrate`.
4. Desde `backend/api`, ejecutar `php artisan db:seed` si quieres sembrar datos.
