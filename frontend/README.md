# Frontend Separado

Esta carpeta es el cliente web independiente del sistema.

## Objetivo
Consumir el backend Laravel via API REST, sin depender de Blade.

## Requisitos
- Node.js instalado
- Backend Laravel corriendo desde `backend/api` en `http://127.0.0.1:8000`

## Ejecutar
1. Entra a esta carpeta: `cd frontend`
2. Instala dependencias: `npm install`
3. Levanta el cliente: `npm run dev`
4. Abre: `http://127.0.0.1:5173`

## API que consume
- `GET /api/v1/products`

## Siguiente paso recomendado
Migrar login, carrito, pedidos y admin hacia componentes JS separados en esta misma carpeta.
