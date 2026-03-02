# Frontend

## Capa frontend actual

El frontend principal ahora esta en `frontend`.

### Donde esta el frontend
- `frontend/src`: codigo del cliente web separado.
- `frontend/public`: imagenes publicas del frontend.
- `frontend/package.json`: dependencias de frontend.
- `frontend/vite.config.js`: compilacion con Vite.

### Estructura principal
- `frontend/src/main.js`: interfaz principal.
- `frontend/src/api.js`: consumo de API.
- `frontend/src/config.js`: URLs de conexion.
- `frontend/src/styles.css`: estilos modernos y responsivos.

### Flujo frontend principal
1. Vite sirve el frontend separado.
2. JavaScript consulta la API del backend.
3. El cliente renderiza productos y vistas en el navegador.
4. La interfaz queda desacoplada del backend Laravel.

## Recomendacion de identificacion
Mantener todo lo visual nuevo en `frontend`.
Las vistas Blade dentro de `backend/api/resources/views` quedan como legado hasta migrarlas por completo.
