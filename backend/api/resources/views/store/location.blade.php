@extends('store.layout')

@section('title', 'El Dorado - Ubicacion')

@section('content')
    <style>
        .location-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px; }
        .location-card {
            background: linear-gradient(170deg, #fff, #fff8f1);
            border: 1px solid #ffd7bd;
            border-radius: 14px;
            padding: 14px;
        }
    </style>
    <h1 class="title">Donde nos ubicamos</h1>
    <section class="location-grid">
        <article class="location-card">
            <p><strong>Direccion principal:</strong> Av. Principal 123, Lima, Peru</p>
            <p><strong>Referencia:</strong> Frente al parque central</p>
            <p><strong>Telefono:</strong> 999 888 777</p>
            <a href="https://maps.google.com/?q=-12.0464,-77.0428" target="_blank" rel="noreferrer" class="btn-main" style="text-decoration:none; display:inline-block;">
                Abrir mapa
            </a>
        </article>
        <article class="location-card">
            Para delivery puedes enviar direccion y referencia. Si activas ubicacion exacta, el despacho llega con
            mas precision.
        </article>
    </section>
@endsection
