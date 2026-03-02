@extends('store.layout')

@section('title', 'El Dorado - Quienes Somos')

@section('content')
    <style>
        .about-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px; }
        .about-card {
            background: linear-gradient(155deg, #fff, #fff7ef);
            border: 1px solid #ffd8bc;
            border-radius: 14px;
            padding: 14px;
        }
        .about-accent {
            background: linear-gradient(120deg, #ff6f1f, #ff9d5a);
            color: #2d1507;
            font-weight: 800;
        }
    </style>
    <h1 class="title">Quienes somos</h1>
    <section class="about-grid">
        <article class="about-card about-accent">
            Pollos y Parrillas "El Dorado" combina sabor tradicional con un servicio digital rapido y claro.
        </article>
        <article class="about-card">
            El Dorado es una polleria a la brasa enfocada en sabor constante, cocina de alto flujo y atencion rapida.
            Nuestro objetivo es que cada pedido llegue jugoso, caliente y con una experiencia digital simple.
        </article>
        <article class="about-card">
            Trabajamos con estandares de preparacion por lote, control de tiempos y seguimiento por estados para
            que el cliente sepa exactamente en que etapa esta su pedido.
        </article>
    </section>
@endsection
