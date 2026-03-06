-- Schema completo para Pollos y Parrillas "El Dorado"
-- Base objetivo: app_pollos_el_dorado
-- Recomendado: ejecutar conectado a esa base.

CREATE EXTENSION IF NOT EXISTS "pgcrypto";

CREATE TABLE IF NOT EXISTS migrations (
    id BIGSERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    phone VARCHAR(30) NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'customer',
    is_active BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS sessions_user_id_index ON sessions(user_id);
CREATE INDEX IF NOT EXISTS sessions_last_activity_index ON sessions(last_activity);

CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS jobs_queue_index ON jobs(queue);

CREATE TABLE IF NOT EXISTS job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INTEGER NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER NULL
);

CREATE TABLE IF NOT EXISTS failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    category VARCHAR(60) NOT NULL DEFAULT 'pollos',
    description TEXT NULL,
    price NUMERIC(10,2) NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0 CHECK (stock >= 0),
    image_url VARCHAR(500) NULL,
    is_available BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS orders (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    tracking_code VARCHAR(20) NOT NULL UNIQUE,
    customer_name VARCHAR(120) NOT NULL,
    customer_phone VARCHAR(30) NOT NULL,
    delivery_type VARCHAR(20) NOT NULL CHECK (delivery_type IN ('pickup', 'delivery')),
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'confirmed', 'preparing', 'on_the_way', 'delivered', 'cancelled')),
    total_amount NUMERIC(10,2) NOT NULL DEFAULT 0,
    payment_method VARCHAR(20) NOT NULL DEFAULT 'cod',
    payment_reference VARCHAR(120) NULL,
    payment_proof_path VARCHAR(500) NULL,
    payment_reported_at TIMESTAMP NULL,
    payment_verified_at TIMESTAMP NULL,
    payment_status VARCHAR(20) NOT NULL DEFAULT 'pending',
    salad_type VARCHAR(20) NULL,
    drink_note VARCHAR(120) NULL,
    address VARCHAR(255) NULL,
    reference VARCHAR(255) NULL,
    latitude NUMERIC(10,7) NULL,
    longitude NUMERIC(10,7) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
CREATE INDEX IF NOT EXISTS orders_user_id_index ON orders(user_id);
CREATE INDEX IF NOT EXISTS orders_status_index ON orders(status);
CREATE INDEX IF NOT EXISTS orders_payment_status_index ON orders(payment_status);
CREATE INDEX IF NOT EXISTS orders_created_at_index ON orders(created_at);

CREATE TABLE IF NOT EXISTS order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    product_id BIGINT NULL REFERENCES products(id) ON DELETE SET NULL,
    product_name VARCHAR(120) NOT NULL,
    unit_price NUMERIC(10,2) NOT NULL,
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    line_total NUMERIC(10,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
CREATE INDEX IF NOT EXISTS order_items_order_id_index ON order_items(order_id);
CREATE INDEX IF NOT EXISTS order_items_product_id_index ON order_items(product_id);

CREATE TABLE IF NOT EXISTS order_status_histories (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    status VARCHAR(20) NOT NULL CHECK (status IN ('pending', 'confirmed', 'preparing', 'on_the_way', 'delivered', 'cancelled')),
    note VARCHAR(255) NULL,
    changed_by BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
CREATE INDEX IF NOT EXISTS order_status_histories_order_id_index ON order_status_histories(order_id);
CREATE INDEX IF NOT EXISTS order_status_histories_changed_by_index ON order_status_histories(changed_by);
