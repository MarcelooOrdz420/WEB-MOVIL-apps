@extends('store.layout')

@section('title', 'El Dorado - Expertos')

@section('content')
    <style>
        .experts-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:12px; }
        .expert-card {
            background: linear-gradient(160deg, #fff 0%, #fff8f2 100%);
            border: 1px solid #ffd8bf;
            border-radius: 14px;
            padding: 14px;
            position: relative;
            overflow: hidden;
        }
        .expert-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255,111,31,.15), transparent 45%);
            pointer-events: none;
        }
        .expert-card strong { color: #6f3200; }
    </style>
    <h1 class="title">En que somos expertos</h1>
    <section class="experts-grid">
        <article class="expert-card">
            <strong>Brasa profesional</strong>
            <p>Coccion uniforme, sabor intenso y punto exacto en cada pieza.</p>
        </article>
        <article class="expert-card">
            <strong>Despacho rapido</strong>
            <p>Flujo de cocina optimizado para horas pico y entregas continuas.</p>
        </article>
        <article class="expert-card">
            <strong>Pedidos online</strong>
            <p>Catalogo, carrito y pago en flujo claro con seguimiento por codigo.</p>
        </article>
        <article class="expert-card">
            <strong>Atencion de volumen</strong>
            <p>Combos familiares y pedidos para eventos.</p>
        </article>
    </section>
@endsection
