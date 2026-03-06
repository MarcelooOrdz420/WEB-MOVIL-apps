@extends('store.layout')

@section('title', 'El Dorado - Carrito')

@section('content')
    <style>
        .cart-layout { display: grid; grid-template-columns: 1.1fr .9fr; gap: 14px; }
        .cart-summary { padding: 0; overflow: hidden; }
        .summary-head { padding: 14px; background: linear-gradient(120deg, #ff6f1f, #ff9d5a); color: #2c1506; font-weight: 800; letter-spacing: .2px; }
        .summary-body { padding: 14px; }
        .cart-note { margin-top: 10px; font-size: 13px; color: #7d5138; }
        .checkout-card { border: 1px solid #ffd2ad; border-radius: 12px; background: linear-gradient(170deg, #fffdfb, #fff8f3); padding: 12px; margin-bottom: 10px; }
        .checkout-card h4 { margin: 0 0 8px; color: #8d3d00; }
        .checkout-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); gap: 8px; }
        .checkout-input { width: 100%; border: 1px solid #edc8a8; border-radius: 9px; padding: 10px; background: #fff; }
        .checkout-label { font-size: 13px; font-weight: 700; color: #55270a; }
        .pay-options { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 8px; }
        .pay-option { border: 1px solid #f2c6a4; border-radius: 10px; background: #fff8f2; padding: 9px; transition: .2s ease; }
        .payment-info { margin-top: 8px; border: 1px solid #ffd9be; border-radius: 10px; padding: 10px; background: #fff; }
        .qr-image { margin-top: 6px; width: 100%; max-width: 160px; aspect-ratio: 1 / 1; object-fit: contain; border: 1px dashed #ffb47d; border-radius: 8px; background: #fff; }
        .qty-btn { margin-left: 6px; border: 1px solid #d4a07e; border-radius: 6px; cursor: pointer; min-width: 28px; min-height: 28px; background: #fff; color: #5c2d0d; }
        .order-submit { margin-top: 8px; width: 100%; }
        .dark-btn {
            border: 1px solid #2f2f31;
            background: #171718;
            color: #fff;
            border-radius: 8px;
            padding: 9px 12px;
            cursor: pointer;
        }
        .processing-overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            background: rgba(16, 10, 6, .58);
            z-index: 90;
        }
        .processing-card {
            width: 100%;
            max-width: 420px;
            border-radius: 18px;
            padding: 18px;
            border: 1px solid #ffc999;
            background: linear-gradient(180deg, #fffdfb 0%, #fff1e5 100%);
            box-shadow: 0 18px 40px rgba(70, 28, 0, .18);
            text-align: center;
        }
        .processing-image {
            width: 96px;
            height: 96px;
            object-fit: contain;
            border-radius: 18px;
            border: 1px solid #ffc999;
            background: #fff;
        }
        .processing-card h3 { margin: 12px 0 6px; color: #8d3d00; }
        .processing-card p { margin: 0; color: #6f431f; }
        @media (max-width: 920px) { .cart-layout { grid-template-columns: 1fr; } }
    </style>
    <h1 class="title">Carrito y Checkout</h1>

    <div class="cart-layout" id="cartGrid">
        <section class="panel cart-summary">
            <div class="summary-head">Resumen del carrito</div>
            <div class="summary-body">
                <div id="cartList">Sin productos agregados.</div>
                <div style="margin-top:10px; font-size:18px;"><strong>Total:</strong> S/ <span id="cartTotal">0.00</span></div>
                <div class="cart-note">Tu carrito se guarda automaticamente.</div>
            </div>
        </section>

        <section class="panel">
            <h3 style="margin-top:0;">Datos de entrega y pago</h3>
            <form id="orderForm">
                <div class="checkout-card">
                    <h4>Datos de cliente</h4>
                    <div class="checkout-grid">
                        <div>
                            <label class="checkout-label">Nombre</label>
                            <input class="checkout-input" name="customer_name" required>
                        </div>
                        <div>
                            <label class="checkout-label">Telefono</label>
                            <input class="checkout-input" name="customer_phone" required>
                        </div>
                    </div>
                </div>

                <div class="checkout-card">
                    <h4>Entrega</h4>
                    <label class="checkout-label">Tipo de entrega</label>
                    <select class="checkout-input" name="delivery_type">
                        <option value="pickup">Recojo en local</option>
                        <option value="delivery">Delivery</option>
                    </select>
                    <div id="etaBox" style="margin:8px 0 2px; font-size:13px; color:#7f4319;">
                        Tiempo estimado: 15 a 25 min (recojo)
                    </div>

                    <div class="checkout-grid">
                        <div>
                            <label class="checkout-label">Direccion (si es delivery)</label>
                            <input class="checkout-input" name="address">
                        </div>
                        <div>
                            <label class="checkout-label">Referencia</label>
                            <input class="checkout-input" name="reference">
                        </div>
                    </div>
                </div>

                <div id="saladWrap" class="checkout-card" style="display:none;">
                    <h4>Preferencia para pollo a la brasa</h4>
                    <label class="checkout-label">Tipo de ensalada</label>
                    <select class="checkout-input" name="salad_type">
                        <option value="">Selecciona...</option>
                        <option value="dulce">Dulce</option>
                        <option value="salada">Salada</option>
                    </select>
                </div>

                <div class="checkout-card">
                    <h4>Bebidas y ubicacion</h4>
                    <label class="checkout-label">Observacion de bebida (opcional)</label>
                    <input class="checkout-input" name="drink_note" placeholder="Ej: Inca Kola sin hielo">

                    <div class="checkout-grid">
                        <div>
                            <label class="checkout-label">Latitud (opcional)</label>
                            <input class="checkout-input" name="latitude" readonly>
                        </div>
                        <div>
                            <label class="checkout-label">Longitud (opcional)</label>
                            <input class="checkout-input" name="longitude" readonly>
                        </div>
                    </div>
                </div>

                <button id="geoBtn" type="button" class="dark-btn">
                    Usar mi ubicacion exacta
                </button>

                <div class="checkout-card">
                    <label style="display:flex; gap:8px; align-items:flex-start; font-size:13px; color:#5b2a0b;">
                        <input type="checkbox" name="accept_terms" style="margin-top:2px;">
                        <span>Acepto los terminos de compra y autorizo el procesamiento de mis datos para gestionar el pedido.</span>
                    </label>
                </div>

                <div class="checkout-card">
                    <h4>Pasarela de pago</h4>
                    <div id="payOptions" class="pay-options">
                        <label class="pay-option" data-method="yape">
                            <input type="radio" name="payment_method" value="yape" checked> Yape
                        </label>
                        <label class="pay-option" data-method="plin">
                            <input type="radio" name="payment_method" value="plin"> Plin
                        </label>
                        <label class="pay-option" data-method="transfer">
                            <input type="radio" name="payment_method" value="transfer"> Transferencia
                        </label>
                        <label class="pay-option" data-method="cod">
                            <input type="radio" name="payment_method" value="cod"> Contraentrega
                        </label>
                    </div>

                    <div id="paymentInfo" class="payment-info"></div>

                    <label class="checkout-label">Codigo de operacion (Yape/Plin/Transferencia)</label>
                    <input class="checkout-input" name="payment_reference" placeholder="Ej: 1234567890">
                </div>

                <button type="submit" class="btn-main order-submit">
                    Confirmar pedido
                </button>
            </form>

            <div id="orderMsg" style="margin-top:10px; font-size:14px;"></div>
            <div id="lastOrderBox" style="display:none; margin-top:10px; border:1px solid #ffd2ad; border-radius:10px; padding:10px; background:#fff8f1;"></div>
        </section>
    </div>

    <div id="processingOverlay" class="processing-overlay">
        <div class="processing-card">
            <img src="/images/ui/processing-chicken.png" alt="Procesando pedido" class="processing-image">
            <h3 id="processingTitle">Espera, estamos procesando tu pedido</h3>
            <p id="processingText">Validando productos, datos de entrega y forma de pago.</p>
        </div>
    </div>
@endsection

@section('scripts')
<script>
const COMPANY = {
    yapeNumber: '999888777',
    plinNumber: '999888777',
    bankName: 'BCP',
    accountNumber: '123-4567890-12',
    cci: '00212300456789012345'
};

const cartListEl = document.getElementById('cartList');
const cartTotalEl = document.getElementById('cartTotal');
const orderForm = document.getElementById('orderForm');
const orderMsg = document.getElementById('orderMsg');
const geoBtn = document.getElementById('geoBtn');
const payOptions = document.getElementById('payOptions');
const paymentInfo = document.getElementById('paymentInfo');
const saladWrap = document.getElementById('saladWrap');
const lastOrderBox = document.getElementById('lastOrderBox');
const etaBox = document.getElementById('etaBox');
const processingOverlay = document.getElementById('processingOverlay');
const processingTitle = document.getElementById('processingTitle');
const processingText = document.getElementById('processingText');

function getToken() { return localStorage.getItem('ed_token'); }
function isLoggedIn() { return Boolean(getToken()); }
function getCart() { return JSON.parse(localStorage.getItem('ed_cart') || '[]'); }
function setCart(cart) {
    localStorage.setItem('ed_cart', JSON.stringify(cart));
    window.dispatchEvent(new Event('storage'));
}
function money(n) { return Number(n).toFixed(2); }

function setProcessingState(visible, title = 'Espera, estamos procesando tu pedido', text = 'Validando productos, datos de entrega y forma de pago.') {
    processingOverlay.style.display = visible ? 'flex' : 'none';
    processingTitle.textContent = title;
    processingText.textContent = text;
}

function hasChickenInCart() {
    return getCart().some(item => String(item.category || '').toLowerCase() === 'pollos');
}

function renderCart() {
    const cart = getCart();
    if (!cart.length) {
        cartListEl.textContent = 'Sin productos agregados.';
        cartTotalEl.textContent = '0.00';
        saladWrap.style.display = 'none';
        return;
    }

    let total = 0;
    cartListEl.innerHTML = cart.map(item => {
        const line = Number(item.price) * Number(item.qty);
        total += line;
        return `
            <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px dashed #ffc597; padding:8px 0;">
                <div>
                    <strong>${item.name}</strong>
                    <div style="font-size:12px; opacity:.75; text-transform:capitalize;">${item.category || 'general'}</div>
                </div>
                <div>
                    S/ ${money(line)}
                    <button data-minus="${item.id}" class="qty-btn">-</button>
                    <button data-plus="${item.id}" class="qty-btn">+</button>
                </div>
            </div>`;
    }).join('');
    cartTotalEl.textContent = money(total);
    saladWrap.style.display = hasChickenInCart() ? 'block' : 'none';

    cartListEl.querySelectorAll('[data-minus]').forEach(btn => {
        btn.addEventListener('click', () => changeQty(Number(btn.getAttribute('data-minus')), -1));
    });
    cartListEl.querySelectorAll('[data-plus]').forEach(btn => {
        btn.addEventListener('click', () => changeQty(Number(btn.getAttribute('data-plus')), 1));
    });
}

function changeQty(productId, delta) {
    const cart = getCart();
    const item = cart.find(i => i.id === productId);
    if (!item) return;
    item.qty += delta;
    setCart(cart.filter(i => i.qty > 0));
    renderCart();
}

function paymentMethod() {
    const checked = orderForm.querySelector('input[name="payment_method"]:checked');
    return checked ? checked.value : 'yape';
}

function updateEta() {
    const type = orderForm.delivery_type.value;
    etaBox.textContent = type === 'delivery'
        ? 'Tiempo estimado: 35 a 55 min (delivery)'
        : 'Tiempo estimado: 15 a 25 min (recojo)';
}

function updatePaymentInfo() {
    const method = paymentMethod();
    payOptions.querySelectorAll('.pay-option').forEach(option => {
        option.style.borderColor = option.getAttribute('data-method') === method ? '#ff6f1f' : '#f2c6a4';
        option.style.background = option.getAttribute('data-method') === method ? '#ffeedf' : '#fff8f2';
    });

    if (method === 'yape') {
        paymentInfo.innerHTML = `
            <strong>Yape Empresa</strong>
            <div style="font-size:13px;">Numero: ${COMPANY.yapeNumber}</div>
            <img src="/images/yape-qr.png" alt="QR Yape" class="qr-image">
            <div style="font-size:12px;opacity:.75;">Ruta QR: /images/yape-qr.png</div>`;
        return;
    }
    if (method === 'plin') {
        paymentInfo.innerHTML = `
            <strong>Plin Empresa</strong>
            <div style="font-size:13px;">Numero: ${COMPANY.plinNumber}</div>
            <img src="/images/plin-qr.png" alt="QR Plin" class="qr-image">
            <div style="font-size:12px;opacity:.75;">Ruta QR: /images/plin-qr.png</div>`;
        return;
    }
    if (method === 'transfer') {
        paymentInfo.innerHTML = `<strong>Transferencia</strong><div style="font-size:13px;">Banco: ${COMPANY.bankName}</div><div style="font-size:13px;">Cuenta: ${COMPANY.accountNumber}</div><div style="font-size:13px;">CCI: ${COMPANY.cci}</div>`;
        return;
    }
    paymentInfo.innerHTML = '<strong>Pago contraentrega</strong><div style="font-size:13px;">Pagas cuando recibes tu pedido.</div>';
}

function showLastOrder() {
    const tracking = localStorage.getItem('ed_last_tracking');
    if (!tracking) return;
    lastOrderBox.style.display = 'block';
    lastOrderBox.innerHTML = `
        <strong>Ultimo pedido:</strong> ${tracking}<br>
        <a href="/mis-pedidos" style="color:#a34b00; font-weight:700;">Ver seguimiento en Mis pedidos</a>
    `;
}

geoBtn.addEventListener('click', () => {
    if (!navigator.geolocation) {
        alert('Tu navegador no soporta geolocalizacion.');
        return;
    }
    navigator.geolocation.getCurrentPosition((position) => {
        orderForm.latitude.value = position.coords.latitude.toFixed(7);
        orderForm.longitude.value = position.coords.longitude.toFixed(7);
    }, () => {
        alert('No se pudo obtener tu ubicacion.');
    });
});

orderForm.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', updatePaymentInfo);
});
orderForm.delivery_type.addEventListener('change', updateEta);

orderForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!isLoggedIn()) {
        window.location.href = '/login';
        return;
    }

    const cart = getCart();
    if (!cart.length) {
        orderMsg.textContent = 'Tu carrito esta vacio.';
        return;
    }
    if (!orderForm.accept_terms.checked) {
        orderMsg.textContent = 'Debes aceptar los terminos de compra para continuar.';
        return;
    }

    const payload = {
        customer_name: orderForm.customer_name.value.trim(),
        customer_phone: orderForm.customer_phone.value.trim(),
        delivery_type: orderForm.delivery_type.value,
        payment_method: paymentMethod(),
        payment_reference: orderForm.payment_reference.value.trim() || null,
        salad_type: orderForm.salad_type ? (orderForm.salad_type.value || null) : null,
        drink_note: orderForm.drink_note.value.trim() || null,
        address: orderForm.address.value.trim() || null,
        reference: orderForm.reference.value.trim() || null,
        latitude: orderForm.latitude.value ? Number(orderForm.latitude.value) : null,
        longitude: orderForm.longitude.value ? Number(orderForm.longitude.value) : null,
        items: cart.map(i => ({ product_id: i.id, quantity: i.qty })),
    };

    setProcessingState(true);
    orderMsg.textContent = 'Procesando pedido...';
    try {
        const res = await fetch('/api/v1/orders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${getToken()}` },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (!res.ok) {
            setProcessingState(false);
            orderMsg.textContent = data.message || 'No se pudo crear el pedido.';
            return;
        }

        localStorage.setItem('ed_last_tracking', data.tracking_code);
        const recent = JSON.parse(localStorage.getItem('ed_recent_trackings') || '[]');
        localStorage.setItem('ed_recent_trackings', JSON.stringify([data.tracking_code, ...recent.filter(v => v !== data.tracking_code)].slice(0, 10)));

        setProcessingState(true, 'Pedido generado correctamente', `Codigo ${data.tracking_code}. Ya puedes revisar el seguimiento.`);
        orderMsg.textContent = `Pedido creado. Codigo: ${data.tracking_code}. Estado de pago: ${data.payment_status || 'pending'}`;
        setCart([]);
        renderCart();
        orderForm.reset();
        updatePaymentInfo();
        showLastOrder();
        setTimeout(() => setProcessingState(false), 1400);
    } catch {
        setProcessingState(false);
        orderMsg.textContent = 'No se pudo conectar al servidor.';
    }
});

renderCart();
updatePaymentInfo();
updateEta();
showLastOrder();
</script>
@endsection
