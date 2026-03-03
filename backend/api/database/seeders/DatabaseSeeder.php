<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@eldorado.pe'],
            [
                'name' => 'Admin El Dorado',
                'phone' => '999888777',
                'role' => 'admin',
                'password' => Hash::make('admin12345'),
            ]
        );

        $products = [
            ['name' => '1/4 Pollo a la Brasa', 'category' => 'pollos', 'description' => 'Con papas y ensalada.', 'price' => 18.90, 'image_url' => '/images/products/pollos/cuarto.jpg'],
            ['name' => '1/2 Pollo a la Brasa', 'category' => 'pollos', 'description' => 'Ideal para compartir.', 'price' => 34.90, 'image_url' => '/images/products/pollos/medio_pollo.jpg'],
            ['name' => 'Pollo Entero a la Brasa', 'category' => 'pollos', 'description' => 'Con papas familiares y cremas.', 'price' => 64.90, 'image_url' => '/images/products/pollos/pollo_familiar.jpg'],
            ['name' => 'Mostrito Tradicional', 'category' => 'pollos', 'description' => '1/4 de pollo con arroz chaufa.', 'price' => 24.90, 'image_url' => '/images/products/pollos/mostrito.jpg'],
            ['name' => 'Mega Combo Familiar', 'category' => 'pollos', 'description' => 'Pollo entero + papas + ensalada + gaseosa 1.5L.', 'price' => 79.90, 'image_url' => '/images/products/pollos/mega-combo.jpg'],

            ['name' => 'Parrilla Mixta', 'category' => 'parrillas', 'description' => 'Churrasco, chorizo, anticucho y papas.', 'price' => 46.90, 'image_url' => '/images/products/parrillas/parrillada-mixta.jpg'],
            ['name' => 'Anticuchos x 4', 'category' => 'parrillas', 'description' => 'Corazon de res a la parrilla.', 'price' => 28.90, 'image_url' => '/images/products/parrillas/anticuchos.jpg'],
            ['name' => 'Churrasco a la Parrilla', 'category' => 'parrillas', 'description' => 'Lomo a la parrilla con guarnicion.', 'price' => 36.90, 'image_url' => '/images/products/parrillas/parrilla_arge.jpg'],
            ['name' => 'Alitas BBQ x 8', 'category' => 'parrillas', 'description' => 'Alitas glaseadas en salsa BBQ.', 'price' => 29.90, 'image_url' => '/images/products/parrillas/alitas-bbq.jpg'],
            ['name' => 'Brochetas de Pollo', 'category' => 'parrillas', 'description' => 'Brochetas con vegetales grillados.', 'price' => 27.90, 'image_url' => '/images/products/parrillas/pollo_parrilla.jpg'],

            ['name' => 'Inca Kola Personal 500ml', 'category' => 'bebidas', 'description' => 'Bebida personal helada.', 'price' => 5.50, 'image_url' => '/images/products/bebidas/inca-kola.jpg'],
            ['name' => 'Coca-Cola Personal 500ml', 'category' => 'bebidas', 'description' => 'Bebida personal helada.', 'price' => 5.50, 'image_url' => '/images/products/bebidas/coca-cola.jpg'],
            ['name' => 'Sprite Personal 500ml', 'category' => 'bebidas', 'description' => 'Bebida personal helada.', 'price' => 5.50, 'image_url' => '/images/products/bebidas/sprite.jpg'],
            ['name' => 'Chicha Morada 1L', 'category' => 'bebidas', 'description' => 'Chicha morada artesanal.', 'price' => 12.90, 'image_url' => '/images/products/bebidas/chicha_1L.jpg'],
            ['name' => 'Maracuya Frozen', 'category' => 'bebidas', 'description' => 'Refrescante bebida frozen.', 'price' => 9.90, 'image_url' => '/images/products/default.svg'],
            ['name' => 'Limonada Frozen', 'category' => 'bebidas', 'description' => 'Limonada frozen de la casa.', 'price' => 9.90, 'image_url' => '/images/products/bebidas/limonada.jpg'],
            ['name' => 'Agua Mineral 625ml', 'category' => 'bebidas', 'description' => 'Agua mineral sin gas.', 'price' => 4.00, 'image_url' => '/images/products/bebidas/agua.jpg'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'category' => $product['category'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'image_url' => $product['image_url'],
                    'is_available' => true,
                ]
            );
        }
    }
}

