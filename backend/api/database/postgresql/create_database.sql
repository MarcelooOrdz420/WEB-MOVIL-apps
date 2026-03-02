-- Crear base de datos para Pollos y Parrillas El Dorado
CREATE DATABASE app_pollos_el_dorado;

-- Si ejecutas este archivo con psql, cambia a la base creada
\connect app_pollos_el_dorado

-- Opcional: extensiones utiles
CREATE EXTENSION IF NOT EXISTS "pgcrypto";
