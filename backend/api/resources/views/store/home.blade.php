<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Dorado - Polleria a la Brasa</title>
    <style>
        :root { --black:#0d0d0d; --orange:#ff6f1f; --orange-soft:#ff9b53; --white:#fff; --cream:#fff7f1; --line:#2a2a2a; --text-dark:#24160f; --ok:#22a35a; }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: var(--black); color: var(--white); }
        .topbar { position: sticky; top: 0; z-index: 50; background: rgba(10,10,10,.95); border-bottom: 1px solid var(--line); backdrop-filter: blur(6px); }
        .container { max-width: 1150px; margin: 0 auto; padding: 0 16px; }
        .topbar-inner { display: flex; justify-content: space-between; align-items: center; gap: 12px; min-height: 70px; }
        .brand { font-weight: 900; letter-spacing: .4px; color: var(--orange); font-size: 23px; }
        .nav { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .nav a, .btn { text-decoration: none; border: 1px solid #333; color: #f4f4f4; background: #141414; border-radius: 999px; padding: 9px 14px; font-size: 13px; cursor: pointer; }
        .nav a:hover, .btn:hover { border-color: var(--orange); }
        .btn-primary { background: linear-gradient(140deg, var(--orange), var(--orange-soft)); color: #2c1506; font-weight: 800; border-color: transparent; }
        .hero { padding: 56px 0 38px; background: radial-gradient(circle at 80% 15%, #2b1708 0%, #101010 35%, #0d0d0d 70%); }
        .hero-grid { display: grid; grid-template-columns: 1.15fr .85fr; gap: 18px; }
        .hero-box { background: linear-gradient(160deg, #1a1a1a, #121212); border: 1px solid #2a2a2a; border-radius: 18px; padding: 22px; }
        .hero h1 { margin: 0; font-size: 38px; line-height: 1.05; }
        .hero h1 span { color: var(--orange); }
        .hero p { color: #d8d8d8; max-width: 680px; }
        .chips { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
        .chip { background: #1b1b1b; border: 1px solid #333; color: #f0f0f0; border-radius: 999px; padding: 7px 11px; font-size: 12px; }
        .session { font-size: 14px; color: #f3c7a5; margin-top: 8px; }
        .section { padding: 28px 0; background: var(--cream); color: var(--text-dark); }
        .section:nth-child(even) { background: #fff; }
        .section h2 { margin-top: 0; color: #2f1a0f; font-size: 30px; }
        .filters { background: #fff; border: 1px solid #ffd6bb; border-radius: 14px; padding: 14px; margin-bottom: 14px; }
        .row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; }
        input, select { width: 100%; border: 1px solid #edc8a8; border-radius: 10px; padding: 11px; background: #fffefc; margin-bottom: 10px; color: #28170e; }
        label { font-size: 13px; font-weight: 700; }
        .muted { font-size: 13px; opacity: .75; margin: 0; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px; }
        .product-card { background: #fff; border: 1px solid #ffd4b3; border-radius: 14px; padding: 14px; display: grid; gap: 8px; }
        .product-card h3 { margin: 0; color: #27160c; font-size: 18px; }
        .price { font-weight: 900; color: #c35300; font-size: 22px; }
        .actions { display: flex; gap: 8px; }
        .card-btn { border: 0; border-radius: 9px; padding: 9px 11px; font-weight: 700; cursor: pointer; }
        .inspect { background: #fff0e4; color: #7e3900; }
        .add { background: #2a180d; color: #fff; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px; }
        .info-card { background: #fff; border: 1px solid #ffd9be; border-radius: 14px; padding: 14px; }
        .checkout { background: #fff; border: 1px solid #ffd2ad; border-radius: 14px; padding: 14px; margin-top: 14px; }
        .cart-list { margin: 10px 0; border: 1px dashed #ffc597; border-radius: 10px; background: #fff8f2; padding: 10px; min-height: 45px; font-size: 14px; }
        .pay-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; margin: 8px 0; }
        .pay-option { border: 1px solid #f2c6a4; border-radius: 10px; background: #fff8f2; padding: 10px; }
        .pay-option.active { border-color: var(--orange); background: #ffeedf; }
        .qr-wrap { border: 1px dashed #ffb881; border-radius: 10px; padding: 8px; text-align: center; background: #fff; margin-top: 7px; }
        .qr-wrap img { max-width: 140px; width: 100%; border-radius: 6px; }
        .timeline { list-style: none; margin: 12px 0 0; padding: 0; display: grid; gap: 8px; }
        .timeline li { border: 1px solid #f0d7c3; border-radius: 10px; padding: 10px; background: #fff8f2; opacity: .55; }
        .timeline li.active { opacity: 1; border-color: var(--orange); background: #fff1e5; }
        .timeline li.done { opacity: 1; border-color: var(--ok); background: #ebfff3; }
        .modal { position: fixed; inset: 0; display: none; place-items: center; background: rgba(0,0,0,.6); z-index: 80; padding: 16px; }
        .modal-card { width: 100%; max-width: 460px; background: #fff; color: #2d1a0f; border-radius: 14px; padding: 18px; border-top: 4px solid var(--orange); }
        @media (max-width: 840px) { .hero-grid { grid-template-columns: 1fr; } .hero h1 { font-size: 32px; } }
    </style>
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <div class="brand">Pollos y parrillas "El Dorado"</div>
        <nav class="nav">
            <a href="#productos">Productos</a>
            <a href="#quienes">Quienes somos</a>
            <a href="#ubicacion">Ubicacion</a>
            <a href="#expertos">Expertos</a>
            <a href="#carrito">Carrito y pago</a>
            <a href="#seguimiento">Seguimiento</a>
            <a href="/login">Login</a>
            <a href="/register">Registro</a>
            <button class="btn" id="logoutBtn" type="button">Cerrar sesion</button>
        </nav>
    </div>
</header>

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-box">
            <h1>Polleria a la Brasa <span>El Dorado</span></h1>
            <p>Sabor intenso, brasas de verdad y despacho rapido. Revisa platos, arma carrito y paga desde el checkout con Yape, Plin, transferencia o contraentrega.</p>
            <div class="chips">
                <span class="chip">Pollo a la brasa premium</span>
                <span class="chip">Pedidos en linea</span>
                <span class="chip">Tracking por codigo</span>
                <span class="chip">Delivery con ubicacion exacta opcional</span>
            </div>
            <div id="sessionLabel" class="session">Invitado</div>
        </div>
        <div class="hero-box">
            <strong>Horario</strong>
            <p class="muted">Lunes a Domingo: 12:00 PM - 11:00 PM</p>
            <strong>Contacto</strong>
            <p class="muted">Pedidos: 999 888 777</p>
            <strong>Direccion</strong>
            <p class="muted">Av. Principal 123, Lima</p>
            <a class="btn btn-primary" href="#productos">Ver menu ahora</a>
        </div>
    </div>
</section>

<section id="productos" class="section">
    <div class="container">
        <h2>Productos</h2>
        <div class="filters">
            <div class="row">
                <div>
                    <label for="searchInput">Busqueda de productos</label>
                    <input id="searchInput" type="text" placeholder="Ej: 1/4 pollo, parrilla, combo...">
                </div>
                <div>
                    <label for="maxPriceInput">Precio maximo (S/)</label>
                    <input id="maxPriceInput" type="number" step="0.10" min="0" placeholder="Ej: 40.00">
                </div>
            </div>
            <p id="filterInfo" class="muted">Mostrando todos los productos.</p>
        </div>
        <div id="products" class="product-grid"></div>
    </div>
</section>

<section id="quienes" class="section">
    <div class="container">
        <h2>Quienes somos</h2>
        <div class="info-grid">
            <article class="info-card">
                <p>Somos una polleria enfocada en calidad constante, atencion rapida y sabor tradicional peruano. Cada pedido se prepara al momento para mantener textura y temperatura ideal.</p>
            </article>
            <article class="info-card">
                <p>Nuestro objetivo es que cada cliente reciba un pollo jugoso, papas crocantes y una experiencia digital simple, segura y sin friccion.</p>
            </article>
        </div>
    </div>
</section>

<section id="ubicacion" class="section">
    <div class="container">
        <h2>Donde nos ubicamos</h2>
        <div class="info-grid">
            <article class="info-card">
                <p><strong>Local principal:</strong><br>Av. Principal 123, Lima, Peru</p>
                <p><strong>Referencia:</strong> Frente al parque central.</p>
                <p><strong>Telefono:</strong> 999 888 777</p>
            </article>
            <article class="info-card">
                <p>Si eliges delivery, puedes enviar tu direccion y opcionalmente tu ubicacion exacta con GPS para mejorar el tiempo de entrega.</p>
                <a class="btn btn-primary" target="_blank" rel="noreferrer" href="https://maps.google.com/?q=-12.0464,-77.0428">Abrir mapa</a>
            </article>
        </div>
    </div>
</section>

<section id="expertos" class="section">
    <div class="container">
        <h2>En que somos expertos</h2>
        <div class="info-grid">
            <article class="info-card"><strong>Brasa profesional:</strong> punto exacto de coccion y sazon dorada.</article>
            <article class="info-card"><strong>Despacho rapido:</strong> flujo optimizado para horas pico.</article>
            <article class="info-card"><strong>Pedidos online:</strong> carrito, pago y seguimiento en una sola web.</article>
            <article class="info-card"><strong>Atencion de eventos:</strong> combos familiares y pedidos grandes.</article>
        </div>
    </div>
</section>

<section id="carrito" class="section">
    <div class="container">
        <h2>Carrito y pago</h2>
        <p class="muted">Puedes inspeccionar sin login, pero para agregar y pagar debes iniciar sesion.</p>

        <div id="cart" class="cart-list">Sin productos agregados.</div>
        <div><strong>Total:</strong> S/ <span id="total">0.00</span></div>

        <div class="checkout">
            <form id="orderForm">
                <div class="row">
                    <div>
                        <label>Nombre</label>
                        <input name="customer_name" required>
                    </div>
                    <div>
                        <label>Telefono</label>
                        <input name="customer_phone" required>
                    </div>
                </div>

                <label>Tipo de entrega</label>
                <select name="delivery_type">
                    <option value="pickup">Recojo en local</option>
                    <option value="delivery">Delivery</option>
                </select>

                <div class="row">
                    <div>
                        <label>Direccion (obligatoria para delivery)</label>
                        <input name="address">
                    </div>
                    <div>
                        <label>Referencia</label>
                        <input name="reference">
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label>Latitud (opcional)</label>
                        <input name="latitude" readonly>
                    </div>
                    <div>
                        <label>Longitud (opcional)</label>
                        <input name="longitude" readonly>
                    </div>
                </div>

                <button class="btn" type="button" id="geoBtn">Usar mi ubicacion exacta</button>

                <h3>Pasarela de pago</h3>
                <div class="pay-grid" id="payGrid">
                    <label class="pay-option" data-method="yape">
                        <input type="radio" name="payment_method" value="yape" checked>
                        <strong>Yape</strong>
                        <div class="muted">QR o numero de empresa</div>
                    </label>
                    <label class="pay-option" data-method="plin">
                        <input type="radio" name="payment_method" value="plin">
                        <strong>Plin</strong>
                        <div class="muted">QR o numero de empresa</div>
                    </label>
                    <label class="pay-option" data-method="transfer">
                        <input type="radio" name="payment_method" value="transfer">
                        <strong>Transferencia</strong>
                        <div class="muted">Cuenta bancaria / CCI</div>
                    </label>
                    <label class="pay-option" data-method="cod">
                        <input type="radio" name="payment_method" value="cod">
                        <strong>Pago contraentrega</strong>
                        <div class="muted">Pagas al recibir</div>
                    </label>
                </div>

                <div id="paymentInfo" class="info-card"></div>

                <label>Codigo de operacion (obligatorio para Yape, Plin, Transferencia)</label>
                <input name="payment_reference" placeholder="Ej: 1234567890">

                <button class="btn btn-primary" style="margin-top:8px; width:100%;" type="submit">Confirmar pedido y pagar</button>
            </form>
            <div id="orderMsg" style="margin-top:10px;"></div>
        </div>
    </div>
</section>

<section id="seguimiento" class="section">
    <div class="container">
        <h2>Seguimiento de pedido</h2>
        <p class="muted">Ingresa tu codigo (ej: ED-ABC12345) para ver estado en tiempo real.</p>
        <div class="row">
            <div>
                <label for="trackInput">Codigo de seguimiento</label>
                <input id="trackInput" type="text" placeholder="ED-XXXXXXXX">
            </div>
            <div style="display:flex; align-items:end;">
                <button id="trackBtn" class="btn btn-primary" type="button">Buscar pedido</button>
            </div>
        </div>
        <div id="trackMsg" class="muted"></div>
        <ul id="timeline" class="timeline">
            <li data-status="pending">Pendiente: pedido recibido.</li>
            <li data-status="confirmed">Confirmado: pedido aceptado.</li>
            <li data-status="preparing">Preparando: en cocina.</li>
            <li data-status="on_the_way">En camino: repartidor en ruta.</li>
            <li data-status="delivered">Entregado.</li>
            <li data-status="cancelled">Cancelado.</li>
        </ul>
    </div>
</section>

<div class="modal" id="productModal">
    <div class="modal-card">
        <h3 id="modalName"></h3>
        <p id="modalDesc"></p>
        <p><strong id="modalPrice"></strong></p>
        <button class="btn" id="closeModalBtn" type="button">Cerrar</button>
    </div>
</div>

<script>
const COMPANY = {
    yapeNumber: "999888777",
    plinNumber: "999888777",
    bankName: "BCP",
    accountNumber: "123-4567890-12",
    cci: "00212300456789012345"
};

const state = { products: [], cart: [] };
const statusOrder = ["pending", "confirmed", "preparing", "on_the_way", "delivered"];

const productsEl = document.getElementById("products");
const cartEl = document.getElementById("cart");
const totalEl = document.getElementById("total");
const orderForm = document.getElementById("orderForm");
const orderMsg = document.getElementById("orderMsg");
const searchInput = document.getElementById("searchInput");
const maxPriceInput = document.getElementById("maxPriceInput");
const filterInfo = document.getElementById("filterInfo");
const modal = document.getElementById("productModal");
const sessionLabel = document.getElementById("sessionLabel");
const logoutBtn = document.getElementById("logoutBtn");
const geoBtn = document.getElementById("geoBtn");
const payGrid = document.getElementById("payGrid");
const paymentInfo = document.getElementById("paymentInfo");
const trackInput = document.getElementById("trackInput");
const trackBtn = document.getElementById("trackBtn");
const trackMsg = document.getElementById("trackMsg");
const timeline = document.getElementById("timeline");

function getToken() { return localStorage.getItem("ed_token"); }
function isLoggedIn() { return Boolean(getToken()); }
function getUser() {
    const raw = localStorage.getItem("ed_user");
    if (!raw) return null;
    try { return JSON.parse(raw); } catch { return null; }
}
function money(n) { return Number(n).toFixed(2); }

function updateSessionUI() {
    const user = getUser();
    sessionLabel.textContent = user ? `Sesion activa: ${user.name}` : "Sesion: invitado";
    logoutBtn.style.display = user ? "inline-block" : "none";
}

function renderCart() {
    if (!state.cart.length) {
        cartEl.textContent = "Sin productos agregados.";
        totalEl.textContent = "0.00";
        return;
    }
    let total = 0;
    cartEl.innerHTML = state.cart.map((item) => {
        const line = item.price * item.qty;
        total += line;
        return `<div>${item.name} x${item.qty} - S/ ${money(line)}</div>`;
    }).join("");
    totalEl.textContent = money(total);
}

function showProduct(product) {
    document.getElementById("modalName").textContent = product.name;
    document.getElementById("modalDesc").textContent = product.description || "Sin descripcion";
    document.getElementById("modalPrice").textContent = `Precio: S/ ${money(product.price)}`;
    modal.style.display = "grid";
}

function addToCart(product) {
    if (!isLoggedIn()) {
        alert("Debes iniciar sesion o registrarte para comprar.");
        window.location.href = "/login";
        return;
    }
    const existing = state.cart.find((i) => i.id === product.id);
    if (existing) existing.qty += 1;
    else state.cart.push({ id: product.id, name: product.name, price: Number(product.price), qty: 1 });
    renderCart();
}

function getFilteredProducts() {
    const query = searchInput.value.trim().toLowerCase();
    const maxPrice = maxPriceInput.value ? Number(maxPriceInput.value) : null;
    return state.products.filter((product) => {
        const nameMatch = !query || product.name.toLowerCase().includes(query);
        const priceMatch = maxPrice === null || Number(product.price) <= maxPrice;
        return nameMatch && priceMatch;
    });
}

function renderProducts() {
    const filtered = getFilteredProducts();
    filterInfo.textContent = filtered.length === state.products.length
        ? "Mostrando todos los productos."
        : `Mostrando ${filtered.length} de ${state.products.length} productos.`;

    if (!filtered.length) {
        productsEl.innerHTML = "<article class=\"product-card\"><strong>No hay productos con esos filtros.</strong></article>";
        return;
    }

    productsEl.innerHTML = filtered.map((product) => `
        <article class="product-card">
            <h3>${product.name}</h3>
            <p class="muted">${product.description || "Especialidad de la casa."}</p>
            <div class="price">S/ ${money(product.price)}</div>
            <div class="actions">
                <button type="button" class="card-btn inspect" data-inspect="${product.id}">Inspeccionar</button>
                <button type="button" class="card-btn add" data-buy="${product.id}">Agregar</button>
            </div>
        </article>
    `).join("");

    productsEl.querySelectorAll("[data-inspect]").forEach((btn) => {
        const id = Number(btn.getAttribute("data-inspect"));
        const product = state.products.find((p) => p.id === id);
        btn.addEventListener("click", () => showProduct(product));
    });

    productsEl.querySelectorAll("[data-buy]").forEach((btn) => {
        const id = Number(btn.getAttribute("data-buy"));
        const product = state.products.find((p) => p.id === id);
        btn.addEventListener("click", () => addToCart(product));
    });
}

function paymentMethod() {
    const checked = orderForm.querySelector("input[name='payment_method']:checked");
    return checked ? checked.value : "yape";
}

function updatePaymentInfo() {
    const method = paymentMethod();
    payGrid.querySelectorAll(".pay-option").forEach((option) => {
        option.classList.toggle("active", option.getAttribute("data-method") === method);
    });

    if (method === "yape") {
        paymentInfo.innerHTML = `
            <strong>Yape Empresa</strong>
            <div class="muted">Numero: ${COMPANY.yapeNumber}</div>
            <div class="qr-wrap">
                <img src="/images/yape-qr.png" alt="QR Yape" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display:none;" class="muted">Sube tu QR real en /public/images/yape-qr.png</div>
            </div>`;
        return;
    }
    if (method === "plin") {
        paymentInfo.innerHTML = `
            <strong>Plin Empresa</strong>
            <div class="muted">Numero: ${COMPANY.plinNumber}</div>
            <div class="qr-wrap">
                <img src="/images/plin-qr.png" alt="QR Plin" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display:none;" class="muted">Sube tu QR real en /public/images/plin-qr.png</div>
            </div>`;
        return;
    }
    if (method === "transfer") {
        paymentInfo.innerHTML = `
            <strong>Transferencia bancaria</strong>
            <div class="muted">Banco: ${COMPANY.bankName}</div>
            <div class="muted">Cuenta: ${COMPANY.accountNumber}</div>
            <div class="muted">CCI: ${COMPANY.cci}</div>`;
        return;
    }
    paymentInfo.innerHTML = "<strong>Pago contraentrega</strong><div class='muted'>Pagas cuando recibes tu pedido.</div>";
}

function paintTimeline(status) {
    const normalized = String(status || "").toLowerCase();
    const currentIdx = statusOrder.indexOf(normalized);
    timeline.querySelectorAll("li").forEach((item) => {
        const itemStatus = item.getAttribute("data-status");
        item.classList.remove("active", "done");
        if (normalized === "cancelled") {
            if (itemStatus === "cancelled") item.classList.add("active");
            return;
        }
        const idx = statusOrder.indexOf(itemStatus);
        if (idx === -1) return;
        if (idx < currentIdx) item.classList.add("done");
        if (idx === currentIdx) item.classList.add("active");
    });
}

async function loadProducts() {
    const res = await fetch("/api/v1/products");
    const data = await res.json();
    state.products = Array.isArray(data) ? data : [];
    renderProducts();
}

async function searchTracking() {
    const code = trackInput.value.trim().toUpperCase();
    if (!code) {
        trackMsg.textContent = "Ingresa un codigo de seguimiento.";
        return;
    }
    trackMsg.textContent = "Buscando pedido...";
    try {
        const res = await fetch(`/api/v1/orders/track/${encodeURIComponent(code)}`);
        const data = await res.json();
        if (!res.ok) {
            trackMsg.textContent = data.message || "Pedido no encontrado.";
            paintTimeline("");
            return;
        }
        trackMsg.textContent = `Estado: ${data.status} | Pago: ${data.payment_method || "n/a"}`;
        paintTimeline(data.status);
    } catch {
        trackMsg.textContent = "No se pudo conectar al servidor.";
    }
}

searchInput.addEventListener("input", renderProducts);
maxPriceInput.addEventListener("input", renderProducts);
trackBtn.addEventListener("click", searchTracking);
orderForm.querySelectorAll("input[name='payment_method']").forEach((radio) => radio.addEventListener("change", updatePaymentInfo));

geoBtn.addEventListener("click", () => {
    if (!navigator.geolocation) {
        alert("Tu navegador no soporta geolocalizacion.");
        return;
    }
    navigator.geolocation.getCurrentPosition((position) => {
        orderForm.latitude.value = position.coords.latitude.toFixed(7);
        orderForm.longitude.value = position.coords.longitude.toFixed(7);
    }, () => {
        alert("No se pudo obtener tu ubicacion.");
    });
});

logoutBtn.addEventListener("click", () => {
    localStorage.removeItem("ed_token");
    localStorage.removeItem("ed_user");
    state.cart = [];
    renderCart();
    updateSessionUI();
});

orderForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    if (!isLoggedIn()) {
        alert("Debes iniciar sesion o registrarte para comprar.");
        window.location.href = "/login";
        return;
    }
    if (!state.cart.length) {
        orderMsg.textContent = "Agrega al menos un producto.";
        return;
    }
    const payload = {
        customer_name: orderForm.customer_name.value.trim(),
        customer_phone: orderForm.customer_phone.value.trim(),
        delivery_type: orderForm.delivery_type.value,
        payment_method: paymentMethod(),
        payment_reference: orderForm.payment_reference.value.trim() || null,
        address: orderForm.address.value.trim() || null,
        reference: orderForm.reference.value.trim() || null,
        latitude: orderForm.latitude.value ? Number(orderForm.latitude.value) : null,
        longitude: orderForm.longitude.value ? Number(orderForm.longitude.value) : null,
        items: state.cart.map((i) => ({ product_id: i.id, quantity: i.qty })),
    };
    orderMsg.textContent = "Procesando pedido...";
    try {
        const res = await fetch("/api/v1/orders", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Authorization": `Bearer ${getToken()}` },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (!res.ok) {
            orderMsg.textContent = data.message || "No se pudo crear el pedido.";
            return;
        }
        orderMsg.textContent = `Pedido creado. Tracking: ${data.tracking_code}`;
        state.cart = [];
        renderCart();
        trackInput.value = data.tracking_code;
        paintTimeline("pending");
        orderForm.reset();
        updatePaymentInfo();
    } catch {
        orderMsg.textContent = "No se pudo conectar al servidor.";
    }
});

document.getElementById("closeModalBtn").addEventListener("click", () => { modal.style.display = "none"; });
modal.addEventListener("click", (e) => { if (e.target === modal) modal.style.display = "none"; });

updateSessionUI();
updatePaymentInfo();
loadProducts();
renderCart();
paintTimeline("");
</script>
</body>
</html>
