# Backend

## Capa backend actual

El backend real ahora esta en `backend/api` y usa Laravel.

### Donde esta el backend
- `backend/api/app/Http/Controllers`: controladores de API y web.
- `backend/api/app/Models`: modelos Eloquent.
- `backend/api/routes/api.php`: endpoints REST.
- `backend/api/routes/web.php`: rutas web de vistas.
- `backend/api/app/Http/Middleware`: filtros de acceso.
- `backend/api/database/seeders`: datos iniciales.
- `backend/api/database/migrations`: estructura de tablas.

### Flujo backend principal
1. El cliente consume rutas web y API.
2. Laravel valida autenticacion, roles y estado de cuenta.
3. Los controladores gestionan productos, carrito, pedidos y pagos.
4. Eloquent persiste la informacion en PostgreSQL.

### Archivos clave del negocio
- `backend/api/app/Http/Controllers/Api/AuthController.php`
- `backend/api/app/Http/Controllers/Api/ProductController.php`
- `backend/api/app/Http/Controllers/Api/OrderController.php`
- `backend/api/app/Http/Controllers/Api/AdminUserController.php`
- `backend/api/app/Models/User.php`
- `backend/api/app/Models/Product.php`
- `backend/api/app/Models/Order.php`

## Recomendacion de identificacion
Mantener toda la logica del backend dentro de `backend/api`.
La raiz del proyecto ahora queda solo como organizador de capas.
