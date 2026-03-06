-- Datos iniciales minimos para Pollos y Parrillas "El Dorado"
-- Requiere ejecutar antes schema_full_app_pollos_el_dorado.sql

INSERT INTO users (name, email, phone, role, is_active, password, created_at, updated_at)
VALUES (
    'Admin El Dorado',
    'admin@eldorado.pe',
    '999888777',
    'admin',
    TRUE,
    crypt('admin12345', gen_salt('bf')),
    NOW(),
    NOW()
)
ON CONFLICT (email) DO UPDATE
SET
    name = EXCLUDED.name,
    phone = EXCLUDED.phone,
    role = EXCLUDED.role,
    is_active = EXCLUDED.is_active,
    updated_at = NOW();

INSERT INTO products (name, category, description, price, stock, image_url, is_available, created_at, updated_at)
VALUES
('1/4 Pollo a la Brasa', 'pollos', 'Con papas y ensalada.', 18.90, 80, '/images/products/pollos/cuarto.jpg', TRUE, NOW(), NOW()),
('1/2 Pollo a la Brasa', 'pollos', 'Ideal para compartir.', 34.90, 60, '/images/products/pollos/medio.jpg', TRUE, NOW(), NOW()),
('Pollo Entero a la Brasa', 'pollos', 'Con papas familiares y cremas.', 64.90, 45, '/images/products/pollos/entero.jpg', TRUE, NOW(), NOW()),
('Mostrito Tradicional', 'pollos', '1/4 de pollo con arroz chaufa.', 24.90, 55, '/images/products/pollos/mostrito.jpg', TRUE, NOW(), NOW()),
('Mega Combo Familiar', 'pollos', 'Pollo entero + papas + ensalada + gaseosa 1.5L.', 79.90, 35, '/images/products/pollos/mega-combo.jpg', TRUE, NOW(), NOW()),
('Parrilla Mixta', 'parrillas', 'Churrasco, chorizo, anticucho y papas.', 46.90, 30, '/images/products/parrillas/parrillada-mixta.jpg', TRUE, NOW(), NOW()),
('Anticuchos x 4', 'parrillas', 'Corazon de res a la parrilla.', 28.90, 40, '/images/products/parrillas/anticuchos.jpg', TRUE, NOW(), NOW()),
('Churrasco a la Parrilla', 'parrillas', 'Lomo a la parrilla con guarnicion.', 36.90, 28, '/images/products/parrillas/parrilla_arge.jpg', TRUE, NOW(), NOW()),
('Alitas BBQ x 8', 'parrillas', 'Alitas glaseadas en salsa BBQ.', 29.90, 36, '/images/products/parrillas/alitas-bbq.jpg', TRUE, NOW(), NOW()),
('Brochetas de Pollo', 'parrillas', 'Brochetas con vegetales grillados.', 27.90, 32, '/images/products/parrillas/pollo_parrilla.jpg', TRUE, NOW(), NOW()),
('Inca Kola Personal 500ml', 'bebidas', 'Bebida personal helada.', 5.50, 120, '/images/products/bebidas/inca-kola.jpg', TRUE, NOW(), NOW()),
('Coca-Cola Personal 500ml', 'bebidas', 'Bebida personal helada.', 5.50, 120, '/images/products/bebidas/coca-cola.jpg', TRUE, NOW(), NOW()),
('Sprite Personal 500ml', 'bebidas', 'Bebida personal helada.', 5.50, 95, '/images/products/bebidas/sprite.jpg', TRUE, NOW(), NOW()),
('Chicha Morada 1L', 'bebidas', 'Chicha morada artesanal.', 12.90, 70, '/images/products/bebidas/chicha_1L.jpg', TRUE, NOW(), NOW()),
('Maracuya Frozen', 'bebidas', 'Refrescante bebida frozen.', 9.90, 65, '/images/products/bebidas/limonadafd.jpg', TRUE, NOW(), NOW()),
('Limonada Frozen', 'bebidas', 'Limonada frozen de la casa.', 9.90, 65, '/images/products/bebidas/limonada.jpg', TRUE, NOW(), NOW()),
('Agua Mineral 625ml', 'bebidas', 'Agua mineral sin gas.', 4.00, 150, '/images/products/bebidas/agua.jpg', TRUE, NOW(), NOW())
ON CONFLICT DO NOTHING;
