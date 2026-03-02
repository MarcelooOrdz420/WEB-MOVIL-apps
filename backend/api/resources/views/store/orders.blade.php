@extends('store.layout')

@section('title', 'El Dorado - Mis pedidos')

@section('content')
    <style>
        .orders-grid { display: grid; gap: 10px; }
        .order-card {
            border: 1px solid #ffd7bd;
            border-radius: 12px;
            padding: 12px;
            background: linear-gradient(170deg, #fff 0%, #fff8f2 100%);
            box-shadow: 0 8px 20px rgba(255, 111, 31, .08);
        }
        .tracker-grid { display: grid; grid-template-columns: 1fr auto; gap: 10px; align-items: end; }
        .timeline-list { list-style: none; margin: 10px 0 0; padding: 0; display: grid; gap: 8px; }
        .timeline-list li { border: 1px solid #f0d7c3; border-radius: 10px; padding: 9px; background: #fff8f2; opacity: .55; }
        @media (max-width: 720px) { .tracker-grid { grid-template-columns: 1fr; } }
    </style>
    <h1 class="title">Mis pedidos y seguimiento</h1>

    <section class="panel">
        <p style="margin-top:0; font-size:14px; color:#6a3a1a;">
            Aqui siempre veras tus pedidos y codigos de seguimiento, incluso si sales del carrito.
        </p>
        <div id="ordersList" class="orders-grid">Cargando pedidos...</div>
    </section>

    <section class="panel">
        <h3 style="margin-top:0;">Buscar por codigo</h3>
        <div class="tracker-grid">
            <div>
                <label for="trackInput">Codigo de seguimiento</label>
                <input id="trackInput" type="text" placeholder="ED-XXXXXXXX">
            </div>
            <button id="trackBtn" type="button" class="btn-main">
                Buscar
            </button>
        </div>
        <div id="trackMsg" style="font-size:13px; opacity:.8; margin-top:8px;"></div>
        <ul id="timeline" class="timeline-list">
            <li data-status="pending">Pendiente</li>
            <li data-status="confirmed">Confirmado</li>
            <li data-status="preparing">Preparando</li>
            <li data-status="on_the_way">En camino</li>
            <li data-status="delivered">Entregado</li>
            <li data-status="cancelled">Cancelado</li>
        </ul>
    </section>
@endsection

@section('scripts')
<script>
const statusOrder = ['pending', 'confirmed', 'preparing', 'on_the_way', 'delivered'];
const STATUS_ES = {
    pending: 'Pendiente',
    confirmed: 'Confirmado',
    preparing: 'Preparando',
    on_the_way: 'En camino',
    delivered: 'Entregado',
    cancelled: 'Cancelado',
};
const PAYMENT_STATUS_ES = {
    pending: 'Pendiente',
    reported: 'Reportado',
    verified: 'Verificado',
    rejected: 'Rechazado',
};
const ordersList = document.getElementById('ordersList');
const trackInput = document.getElementById('trackInput');
const trackBtn = document.getElementById('trackBtn');
const trackMsg = document.getElementById('trackMsg');
const timeline = document.getElementById('timeline');

function getToken() { return localStorage.getItem('ed_token'); }
function statusEs(code) { return STATUS_ES[code] || code || 'n/a'; }
function paymentStatusEs(code) { return PAYMENT_STATUS_ES[code] || code || 'n/a'; }

function paintTimeline(status) {
    const normalized = String(status || '').toLowerCase();
    const currentIdx = statusOrder.indexOf(normalized);
    timeline.querySelectorAll('li').forEach(item => {
        const itemStatus = item.getAttribute('data-status');
        item.style.opacity = '.55';
        item.style.borderColor = '#f0d7c3';
        item.style.background = '#fff8f2';

        if (normalized === 'cancelled') {
            if (itemStatus === 'cancelled') {
                item.style.opacity = '1';
                item.style.borderColor = '#ff6f1f';
                item.style.background = '#fff1e5';
            }
            return;
        }

        const idx = statusOrder.indexOf(itemStatus);
        if (idx === -1) return;
        if (idx < currentIdx) {
            item.style.opacity = '1';
            item.style.borderColor = '#22a35a';
            item.style.background = '#ebfff3';
        }
        if (idx === currentIdx) {
            item.style.opacity = '1';
            item.style.borderColor = '#ff6f1f';
            item.style.background = '#fff1e5';
        }
    });
}

async function fetchMyOrders() {
    const token = getToken();
    if (!token) {
        ordersList.innerHTML = '<strong>Debes iniciar sesion para ver tus pedidos.</strong>';
        return;
    }

    try {
        const res = await fetch('/api/v1/orders/my', {
            headers: { 'Authorization': `Bearer ${token}` },
        });
        const data = await res.json();
        if (!res.ok) {
            ordersList.innerHTML = '<strong>No se pudo cargar tus pedidos.</strong>';
            return;
        }

        if (!Array.isArray(data) || !data.length) {
            ordersList.innerHTML = '<strong>Aun no tienes pedidos.</strong>';
            return;
        }

        ordersList.innerHTML = data.map(order => `
            <article class="order-card">
                <div><strong>Codigo:</strong> ${order.tracking_code}</div>
                <div><strong>Fecha/Hora:</strong> ${new Date(order.created_at).toLocaleString()}</div>
                <div><strong>Estado:</strong> ${statusEs(order.status)}</div>
                <div><strong>Total:</strong> S/ ${Number(order.total_amount).toFixed(2)}</div>
                <div><strong>Pago:</strong> ${order.payment_method || 'n/a'} | <strong>Estado pago:</strong> ${paymentStatusEs(order.payment_status)}</div>
                <div><strong>Operacion:</strong> ${order.payment_reference || 'sin codigo'}</div>
                <div><strong>Comprobante:</strong> ${order.payment_proof_path ? `<a href="${order.payment_proof_path}" target="_blank">Ver archivo</a>` : 'no subido'}</div>
                <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:6px;">
                    <button data-track="${order.tracking_code}" class="btn-soft">Ver seguimiento</button>
                    <button data-view-receipt="${order.id}" class="btn-soft">Ver boleta</button>
                    <button data-download="${order.id}" class="btn-soft">Descargar boleta</button>
                </div>
                <div style="margin-top:8px;">
                    <input type="file" data-proof-file="${order.id}" accept=\".jpg,.jpeg,.png,.webp,.pdf\">
                    <button data-proof-upload="${order.id}" class="btn-soft">Subir comprobante</button>
                </div>
            </article>
        `).join('');

        ordersList.querySelectorAll('[data-track]').forEach(btn => {
            btn.addEventListener('click', () => {
                trackInput.value = btn.getAttribute('data-track');
                searchTracking();
            });
        });
        ordersList.querySelectorAll('[data-download]').forEach(btn => {
            btn.addEventListener('click', () => downloadReceipt(Number(btn.getAttribute('data-download'))));
        });
        ordersList.querySelectorAll('[data-view-receipt]').forEach(btn => {
            btn.addEventListener('click', () => viewReceipt(Number(btn.getAttribute('data-view-receipt'))));
        });
        ordersList.querySelectorAll('[data-proof-upload]').forEach(btn => {
            btn.addEventListener('click', () => uploadProof(Number(btn.getAttribute('data-proof-upload'))));
        });

        const last = localStorage.getItem('ed_last_tracking');
        if (last) {
            trackInput.value = last;
            searchTracking();
        }
    } catch {
        ordersList.innerHTML = '<strong>Error de conexion al cargar pedidos.</strong>';
    }
}

async function searchTracking() {
    const code = trackInput.value.trim().toUpperCase();
    if (!code) {
        trackMsg.textContent = 'Ingresa un codigo de seguimiento.';
        return;
    }
    trackMsg.textContent = 'Buscando...';
    try {
        const res = await fetch(`/api/v1/orders/track/${encodeURIComponent(code)}`);
        const data = await res.json();
        if (!res.ok) {
            trackMsg.textContent = data.message || 'Pedido no encontrado.';
            paintTimeline('');
            return;
        }
        trackMsg.textContent = `Estado: ${statusEs(data.status)} | Pago: ${data.payment_method || 'n/a'} | Pago estado: ${paymentStatusEs(data.payment_status)} | Operacion: ${data.payment_reference || 'sin codigo'}`;
        paintTimeline(data.status);
    } catch {
        trackMsg.textContent = 'No se pudo conectar al servidor.';
    }
}

async function uploadProof(orderId) {
    const token = getToken();
    const fileInput = document.querySelector(`[data-proof-file="${orderId}"]`);
    const file = fileInput?.files?.[0];
    if (!file) {
        alert('Selecciona un archivo primero');
        return;
    }

    const formData = new FormData();
    formData.append('proof', file);

    try {
        const res = await fetch(`/api/v1/orders/${orderId}/payment-proof`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}` },
            body: formData,
        });
        const data = await res.json();
        if (!res.ok) {
            alert(data.message || 'No se pudo subir comprobante');
            return;
        }
        alert('Comprobante subido correctamente');
        fetchMyOrders();
    } catch {
        alert('Error de conexion al subir comprobante');
    }
}

async function downloadReceipt(orderId) {
    const token = getToken();
    try {
        const res = await fetch(`/api/v1/orders/${orderId}/receipt`, {
            headers: { 'Authorization': `Bearer ${token}` },
        });
        if (!res.ok) {
            alert('No se pudo descargar ticket');
            return;
        }
        const blob = await res.blob();
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `boleta-${orderId}.html`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    } catch {
        alert('Error de conexion al descargar ticket');
    }
}

function viewReceipt(orderId) {
    const token = getToken();
    if (!token) {
        alert('Debes iniciar sesion');
        return;
    }
    const url = `/api/v1/orders/${orderId}/receipt-view?token_preview=1`;
    fetch(url, {
        headers: { 'Authorization': `Bearer ${token}` },
    }).then(async (res) => {
        if (!res.ok) {
            alert('No se pudo abrir la boleta');
            return;
        }
        const html = await res.text();
        const win = window.open('', '_blank');
        if (!win) {
            alert('Tu navegador bloqueo la ventana emergente');
            return;
        }
        win.document.open();
        win.document.write(html);
        win.document.close();
    }).catch(() => alert('Error de conexion al abrir boleta'));
}

trackBtn.addEventListener('click', searchTracking);
paintTimeline('');
fetchMyOrders();
</script>
@endsection
