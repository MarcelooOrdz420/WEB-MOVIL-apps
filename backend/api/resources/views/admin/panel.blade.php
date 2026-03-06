<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="/images/ico-pollo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="/images/ico-pollo.jpg">
    <title>Pollos y Parrillas El Dorado - Panel Admin</title>
    <style>
        :root {
            --black: #fff8f2;
            --black-soft: #fff3e7;
            --orange: #ff6f1f;
            --orange-soft: #ff9f62;
            --line: #ffd7bd;
            --text: #2b190f;
            --bg: #fff8f2;
            --panel: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, #fff8f2 0%, #fff3e8 100%);
            color: var(--text);
        }

        .container { max-width: 1220px; margin: 0 auto; padding: 0 16px; }

        header {
            position: sticky;
            top: 0;
            z-index: 30;
            backdrop-filter: blur(6px);
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid var(--line);
        }

        .head {
            min-height: 68px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .title { color: var(--orange); font-weight: 900; letter-spacing: .4px; }
        .user { font-size: 14px; color: #8d480f; }

        .layout {
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            gap: 14px;
            padding: 16px 0 30px;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 12px 26px rgba(52, 17, 0, .06);
        }

        .panel h2, .panel h3 { margin-top: 0; }
        .row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 8px; }

        input, select, textarea {
            width: 100%;
            border: 1px solid #edc8a8;
            border-radius: 9px;
            background: #fffdfb;
            color: #2b190f;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 8px;
        }

        button {
            border: 1px solid #edc8a8;
            border-radius: 8px;
            background: #fff4ea;
            color: #6c3306;
            cursor: pointer;
            padding: 8px 11px;
        }

        .btn-main {
            border: 0;
            background: linear-gradient(120deg, var(--orange), var(--orange-soft));
            color: #2b1406;
            font-weight: 800;
        }

        .list {
            display: grid;
            gap: 8px;
            max-height: 530px;
            overflow: auto;
            padding-right: 3px;
        }

        .card {
            position: relative;
            overflow: hidden;
            border: 1px solid #ffd6ba;
            border-radius: 10px;
            padding: 10px;
            background: linear-gradient(180deg, #fff 0%, #fff8f2 100%);
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }

        .card::after {
            content: "";
            position: absolute;
            inset: auto -40px -40px auto;
            width: 110px;
            height: 110px;
            background: radial-gradient(circle, rgba(255, 111, 31, .11), transparent 68%);
            pointer-events: none;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            align-items: center;
        }

        .tag {
            background: #fff0e4;
            border: 1px solid #ffc89d;
            color: #914406;
            border-radius: 999px;
            font-size: 11px;
            padding: 4px 8px;
            text-transform: capitalize;
        }

        .img-thumb {
            width: 100%;
            aspect-ratio: 4 / 3;
            min-height: 110px;
            max-height: 140px;
            object-fit: cover;
            object-position: center;
            border-radius: 8px;
            margin-top: 6px;
            margin-bottom: 6px;
            background: #fff1e4;
            border: 1px solid #ffd6ba;
            transition: transform .28s ease, box-shadow .28s ease, filter .28s ease;
        }

        .muted { font-size: 12px; opacity: .78; color: #6e4329; }
        .msg { font-size: 13px; min-height: 20px; }
        .dashboard-grid { display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-bottom: 14px; }
        .admin-tools-grid { display:grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px; }
        .alert-box {
            border: 1px solid #ffd2b4;
            background: linear-gradient(180deg, #fff7ef 0%, #fff1e5 100%);
            border-radius: 12px;
            padding: 12px;
        }
        .alert-list { display: grid; gap: 8px; margin-top: 8px; }
        .alert-item {
            border: 1px solid #ffc08f;
            border-radius: 10px;
            background: #fff;
            padding: 8px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }
        .critical { color: #b12400; font-weight: 800; }
        .low { color: #9a4a00; font-weight: 700; }
        .settings-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 8px;
            align-items: end;
        }
        .chart-card {
            position: relative;
            overflow: hidden;
            border:1px solid #ffd7bd;
            border-radius:12px;
            padding:12px;
            background:linear-gradient(180deg,#fff 0%,#fff8f2 100%);
        }
        .chart-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 20%, rgba(255,255,255,.3) 50%, transparent 76%);
            transform: translateX(-140%);
            animation: chartSweep 4.4s ease-in-out infinite;
            pointer-events: none;
        }
        .chart-head { display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:8px; }
        .bars { display:grid; grid-template-columns: repeat(auto-fit, minmax(34px, 1fr)); gap:6px; min-height:140px; align-items:end; }
        .bar-col { display:grid; gap:5px; justify-items:center; font-size:10px; color:#7c4219; }
        .bar-fill {
            width:100%;
            max-width:28px;
            border-radius:10px 10px 5px 5px;
            background:linear-gradient(180deg,#ffb071,#ff6f1f);
            box-shadow: 0 8px 14px rgba(255, 111, 31, .18);
            animation: pulseBar 2.8s ease-in-out infinite;
            transform-origin: bottom;
        }

        .card:hover {
            transform: translateY(-3px);
            border-color: #ffbe92;
            box-shadow: 0 14px 26px rgba(255, 111, 31, .10);
        }

        .card:hover .img-thumb {
            transform: scale(1.04);
            filter: saturate(1.05);
            box-shadow: 0 10px 18px rgba(255, 111, 31, .10);
        }

        @keyframes chartSweep {
            0%, 15% { transform: translateX(-140%); }
            45%, 100% { transform: translateX(140%); }
        }

        @keyframes pulseBar {
            0%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(1.03); }
        }

        @media (max-width: 980px) {
            .layout { grid-template-columns: 1fr; }
            .dashboard-grid { grid-template-columns: 1fr; }
            .admin-tools-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<header>
    <div class="container head">
        <div class="title">POLLOS Y PARRILLAS "EL DORADO" | PANEL ADMIN</div>
        <div style="display:flex; align-items:center; gap:8px;">
            <div class="user" id="adminUserLabel">Validando sesion...</div>
            <button id="adminLogoutBtn" style="border:1px solid #ffc89d; border-radius:999px; padding:7px 11px; background:#fff4ea; color:#7c3906; cursor:pointer; font-weight:700;">Cerrar sesion</button>
        </div>
    </div>
</header>

<div class="container">
    <div id="denyBox" class="panel" style="display:none; margin-top:16px;">
        <h2>Acceso denegado</h2>
        <p>Necesitas iniciar sesion como administrador.</p>
        <a href="/admin/login" style="color:#ffb387;">Ir a login admin</a>
    </div>

    <div id="adminContent" class="layout" style="display:none;">
        <section class="panel" style="grid-column: 1 / -1;">
            <h2>Dashboard de ventas</h2>
            <p class="muted">Resumen visual de ventas por dia, mes y ano a partir de los pedidos cargados.</p>
            <div id="salesDashboard" class="dashboard-grid">
                <div class="chart-card">
                    <div class="chart-head">
                        <strong>Ventas por dia</strong>
                        <span class="tag">0</span>
                    </div>
                    <div class="muted">Aun sin datos.</div>
                </div>
                <div class="chart-card">
                    <div class="chart-head">
                        <strong>Ventas por mes</strong>
                        <span class="tag">0</span>
                    </div>
                    <div class="muted">Aun sin datos.</div>
                </div>
                <div class="chart-card">
                    <div class="chart-head">
                        <strong>Ventas por ano</strong>
                        <span class="tag">0</span>
                    </div>
                    <div class="muted">Aun sin datos.</div>
                </div>
            </div>
            <div class="admin-tools-grid">
                <article class="alert-box">
                    <h3 style="margin:0;">Alerta de inventario</h3>
                    <p class="muted">Productos cercanos a agotarse. Se actualiza automaticamente.</p>
                    <div id="lowStockList" class="alert-list">
                        <div class="muted">Sin datos de inventario.</div>
                    </div>
                </article>
                <article class="alert-box">
                    <h3 style="margin:0;">Configuracion del panel</h3>
                    <p class="muted">Ajusta umbral de alerta y refresco automatico.</p>
                    <div class="settings-row">
                        <div>
                            <label>Alerta de bajo stock (<=)</label>
                            <input id="stockThresholdInput" type="number" min="0" step="1" value="10">
                        </div>
                        <div>
                            <label>Refresco automatico (seg)</label>
                            <input id="refreshSecondsInput" type="number" min="10" step="5" value="20">
                        </div>
                        <div>
                            <button id="saveAdminSettingsBtn" class="btn-main">Guardar configuracion</button>
                        </div>
                    </div>
                    <div id="settingsMsg" class="msg"></div>
                </article>
            </div>
        </section>

        <section class="panel">
            <h2>Gestion de Productos</h2>
            <form id="productForm">
                <input type="hidden" name="product_id">
                <div class="row">
                    <div>
                        <label>Nombre</label>
                        <input name="name" required>
                    </div>
                    <div>
                        <label>Precio (S/)</label>
                        <input name="price" type="number" min="0" step="0.10" required>
                    </div>
                    <div>
                        <label>Stock disponible</label>
                        <input name="stock" type="number" min="0" step="1" value="0" required>
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label>Categoria</label>
                        <select name="category" id="categorySelect" required></select>
                    </div>
                    <div>
                        <label>Nueva categoria (opcional)</label>
                        <input id="newCategoryInput" placeholder="Ej: postres">
                    </div>
                </div>
                <label>Descripcion</label>
                <textarea name="description" rows="2"></textarea>
                <label>Ruta de imagen (ej: /images/products/pollos/cuarto.jpg)</label>
                <input name="image_url">
                <div class="row">
                    <label><input type="checkbox" name="is_available" checked> Disponible</label>
                </div>
                <div style="display:flex; gap:8px;">
                    <button type="submit" class="btn-main">Guardar producto</button>
                    <button type="button" id="cancelEditBtn">Cancelar edicion</button>
                </div>
                <div id="productMsg" class="msg"></div>
            </form>
        </section>

        <section class="panel">
            <h3>Lista de productos</h3>
            <div id="productsList" class="list"></div>
        </section>

        <section class="panel">
            <h2>Pedidos recientes</h2>
            <div class="row">
                <div>
                    <label>Estado pedido</label>
                    <select id="filterStatus">
                        <option value="">Todos</option>
                        <option value="pending">Pendiente</option>
                        <option value="confirmed">Confirmado</option>
                        <option value="preparing">Preparando</option>
                        <option value="on_the_way">En camino</option>
                        <option value="delivered">Entregado</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
                <div>
                    <label>Estado pago</label>
                    <select id="filterPaymentStatus">
                        <option value="">Todos</option>
                        <option value="pending">Pendiente</option>
                        <option value="reported">Reportado</option>
                        <option value="verified">Verificado</option>
                        <option value="rejected">Rechazado</option>
                    </select>
                </div>
                <div>
                    <label>Desde</label>
                    <input id="filterDateFrom" type="date">
                </div>
                <div>
                    <label>Hasta</label>
                    <input id="filterDateTo" type="date">
                </div>
            </div>
            <div style="display:flex; gap:8px; margin-bottom:8px;">
                <button id="applyFiltersBtn">Aplicar filtros</button>
                <button id="clearFiltersBtn">Limpiar</button>
                <button id="exportCsvBtn" class="btn-main">Exportar CSV</button>
            </div>
            <div id="ordersList" class="list"></div>
        </section>

        <section class="panel">
            <h3>Actualizar estado</h3>
            <form id="statusForm">
                <label>Pedido ID</label>
                <input name="order_id" required>
                <label>Nuevo estado</label>
                <select name="status" required>
                    <option value="pending">Pendiente</option>
                    <option value="confirmed">Confirmado</option>
                    <option value="preparing">Preparando</option>
                    <option value="on_the_way">En camino</option>
                    <option value="delivered">Entregado</option>
                    <option value="cancelled">Cancelado</option>
                </select>
                <label>Nota (opcional)</label>
                <input name="note">
                <button type="submit" class="btn-main">Actualizar estado</button>
                <div id="statusMsg" class="msg"></div>
            </form>
            <hr style="border-color:#ffd7bd; margin:14px 0;">
            <h3>Validar pago QR/transferencia</h3>
            <form id="paymentForm">
                <label>Pedido ID</label>
                <input name="order_id" required>
                <label>Estado de pago</label>
                <select name="payment_status" required>
                    <option value="pending">Pendiente</option>
                    <option value="reported">Reportado</option>
                    <option value="verified">Verificado</option>
                    <option value="rejected">Rechazado</option>
                </select>
                <label>Codigo operacion (opcional)</label>
                <input name="payment_reference">
                <label>Nota (opcional)</label>
                <input name="note">
                <button type="submit" class="btn-main">Actualizar pago</button>
                <div id="paymentMsg" class="msg"></div>
            </form>
        </section>

        <section class="panel" style="grid-column: 1 / -1;">
            <h2>Gestion de cuentas</h2>
            <p class="muted">Controla cuentas registradas, tiempo de creación y estado activo.</p>
            <div id="usersList" class="list"></div>
        </section>
    </div>
</div>

<script>
const denyBox = document.getElementById('denyBox');
const adminContent = document.getElementById('adminContent');
const adminUserLabel = document.getElementById('adminUserLabel');
const adminLogoutBtn = document.getElementById('adminLogoutBtn');

const productForm = document.getElementById('productForm');
const productMsg = document.getElementById('productMsg');
const categorySelect = document.getElementById('categorySelect');
const newCategoryInput = document.getElementById('newCategoryInput');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const productsList = document.getElementById('productsList');

const statusForm = document.getElementById('statusForm');
const statusMsg = document.getElementById('statusMsg');
const paymentForm = document.getElementById('paymentForm');
const paymentMsg = document.getElementById('paymentMsg');
const ordersList = document.getElementById('ordersList');
const filterStatus = document.getElementById('filterStatus');
const filterPaymentStatus = document.getElementById('filterPaymentStatus');
const filterDateFrom = document.getElementById('filterDateFrom');
const filterDateTo = document.getElementById('filterDateTo');
const applyFiltersBtn = document.getElementById('applyFiltersBtn');
const clearFiltersBtn = document.getElementById('clearFiltersBtn');
const exportCsvBtn = document.getElementById('exportCsvBtn');
const usersList = document.getElementById('usersList');
const salesDashboard = document.getElementById('salesDashboard');
const lowStockList = document.getElementById('lowStockList');
const stockThresholdInput = document.getElementById('stockThresholdInput');
const refreshSecondsInput = document.getElementById('refreshSecondsInput');
const saveAdminSettingsBtn = document.getElementById('saveAdminSettingsBtn');
const settingsMsg = document.getElementById('settingsMsg');

const BASE_CATEGORIES = ['pollos', 'parrillas', 'bebidas'];
const ADMIN_TIMEOUT_MS = 30 * 60 * 1000;
const ADMIN_SETTINGS_KEY = 'ed_admin_settings';
let productsCache = [];
let refreshTimer = null;

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

function getToken() { return localStorage.getItem('ed_token'); }
function getUser() {
    const raw = localStorage.getItem('ed_user');
    if (!raw) return null;
    try { return JSON.parse(raw); } catch { return null; }
}

function parseSession() {
    const raw = localStorage.getItem('ed_session');
    if (!raw) return null;
    try { return JSON.parse(raw); } catch { return null; }
}

function saveSession(session) {
    localStorage.setItem('ed_session', JSON.stringify(session));
}

function touchAdminSession() {
    const session = parseSession();
    if (!session || session.role !== 'admin') return;
    session.lastActivity = Date.now();
    session.expiresAt = Date.now() + ADMIN_TIMEOUT_MS;
    saveSession(session);
}

function clearAuth() {
    localStorage.removeItem('ed_token');
    localStorage.removeItem('ed_user');
    localStorage.removeItem('ed_session');
}

function statusEs(code) {
    return STATUS_ES[code] || code || 'n/a';
}

function paymentStatusEs(code) {
    return PAYMENT_STATUS_ES[code] || code || 'n/a';
}

function money(value) {
    return `S/ ${Number(value || 0).toFixed(2)}`;
}

async function parseResponse(res) {
    const raw = await res.text();
    try {
        return JSON.parse(raw);
    } catch {
        return { message: raw ? raw.slice(0, 220) : 'Respuesta no JSON del servidor.' };
    }
}

function canUseAdmin() {
    const user = getUser();
    return Boolean(user && user.role === 'admin' && getToken());
}

function getAdminSettings() {
    const defaults = { stockThreshold: 10, refreshSeconds: 20 };
    const raw = localStorage.getItem(ADMIN_SETTINGS_KEY);
    if (!raw) return defaults;
    try {
        const parsed = JSON.parse(raw);
        return {
            stockThreshold: Number(parsed.stockThreshold ?? defaults.stockThreshold),
            refreshSeconds: Number(parsed.refreshSeconds ?? defaults.refreshSeconds),
        };
    } catch {
        return defaults;
    }
}

function saveAdminSettings() {
    const stockThreshold = Math.max(0, Number(stockThresholdInput.value || 0));
    const refreshSeconds = Math.max(10, Number(refreshSecondsInput.value || 20));
    const settings = { stockThreshold, refreshSeconds };
    localStorage.setItem(ADMIN_SETTINGS_KEY, JSON.stringify(settings));
    settingsMsg.textContent = `Configuracion guardada. Alerta <= ${stockThreshold} y refresco cada ${refreshSeconds}s`;
    startAutoRefresh();
    renderLowStockAlerts();
}

function applyAdminSettingsToForm() {
    const settings = getAdminSettings();
    stockThresholdInput.value = String(settings.stockThreshold);
    refreshSecondsInput.value = String(settings.refreshSeconds);
}

function renderLowStockAlerts() {
    if (!lowStockList) return;
    const settings = getAdminSettings();
    const threshold = Number(settings.stockThreshold || 0);
    const risky = productsCache
        .filter(product => Number(product.stock || 0) <= threshold)
        .sort((a, b) => Number(a.stock || 0) - Number(b.stock || 0));

    if (!risky.length) {
        lowStockList.innerHTML = '<div class="muted">Todo el inventario esta por encima del umbral.</div>';
        return;
    }

    lowStockList.innerHTML = risky.map(product => {
        const stock = Number(product.stock || 0);
        const levelClass = stock <= 0 ? 'critical' : 'low';
        const levelText = stock <= 0 ? 'agotado' : 'bajo stock';
        return `
            <article class="alert-item">
                <div>
                    <strong>${product.name}</strong>
                    <div class="muted">${product.category || 'general'} | ${levelText}</div>
                </div>
                <div class="${levelClass}">Stock: ${stock}</div>
            </article>
        `;
    }).join('');
}

function upsertCategoryOptions() {
    const categories = new Set(BASE_CATEGORIES);
    productsCache.forEach(product => {
        if (product.category) categories.add(String(product.category).toLowerCase());
    });

    const selected = categorySelect.value;
    categorySelect.innerHTML = [...categories]
        .sort()
        .map(category => `<option value="${category}">${category}</option>`)
        .join('');

    if (selected && [...categories].includes(selected)) categorySelect.value = selected;
}

function clearProductForm() {
    productForm.reset();
    productForm.product_id.value = '';
    productForm.is_available.checked = true;
    newCategoryInput.value = '';
}

function productCard(product) {
    const image = product.image_url || '/images/products/default.svg';
    return `
        <article class="card">
            <div class="card-top">
                <strong>${product.name}</strong>
                <span class="tag">${product.category || 'general'}</span>
            </div>
            <img src="${image}" alt="${product.name}" class="img-thumb" onerror="this.onerror=null;this.src='/images/products/default.svg';">
            <div class="muted">${product.description || 'Sin descripcion'}</div>
            <div style="margin-top:6px; font-weight:800;">S/ ${Number(product.price).toFixed(2)}</div>
            <div class="muted">Stock: ${Number(product.stock || 0)}</div>
            <div class="muted">ID: ${product.id}</div>
            <div style="display:flex; gap:8px; margin-top:8px;">
                <button data-edit="${product.id}">Editar</button>
                <button data-delete="${product.id}" style="border-color:#ffc1b5; color:#a53216;">Eliminar</button>
            </div>
        </article>`;
}

function formatBucketLabel(date, mode) {
    if (mode === 'day') {
        return date.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit' });
    }
    if (mode === 'month') {
        return date.toLocaleDateString('es-PE', { month: 'short', year: '2-digit' }).replace('.', '');
    }
    return String(date.getFullYear());
}

function buildBuckets(orders, mode) {
    const map = new Map();
    orders.forEach(order => {
        const sourceDate = new Date(order.created_at);
        if (Number.isNaN(sourceDate.getTime())) return;
        const key = mode === 'day'
            ? sourceDate.toISOString().slice(0, 10)
            : mode === 'month'
                ? `${sourceDate.getFullYear()}-${String(sourceDate.getMonth() + 1).padStart(2, '0')}`
                : `${sourceDate.getFullYear()}`;
        const current = map.get(key) || { total: 0, count: 0, date: sourceDate };
        current.total += Number(order.total_amount || 0);
        current.count += 1;
        current.date = sourceDate;
        map.set(key, current);
    });

    return [...map.entries()]
        .sort((a, b) => a[0].localeCompare(b[0]))
        .slice(-6)
        .map(([, value]) => ({
            label: formatBucketLabel(value.date, mode),
            total: value.total,
            count: value.count,
        }));
}

function renderChart(title, rows) {
    if (!rows.length) {
        return `
            <div class="chart-card">
                <div class="chart-head">
                    <strong>${title}</strong>
                    <span class="tag">0</span>
                </div>
                <div class="muted">Sin pedidos para mostrar.</div>
            </div>`;
    }

    const max = Math.max(...rows.map(item => item.total), 1);

    return `
        <div class="chart-card">
            <div class="chart-head">
                <strong>${title}</strong>
                <span class="tag">${money(rows.reduce((sum, item) => sum + item.total, 0))}</span>
            </div>
            <div class="bars">
                ${rows.map(item => `
                    <div class="bar-col" title="${item.label}: ${money(item.total)} en ${item.count} pedidos">
                        <div class="bar-fill" style="height:${Math.max(16, Math.round((item.total / max) * 110))}px;"></div>
                        <strong>${item.label}</strong>
                        <span>${money(item.total)}</span>
                    </div>
                `).join('')}
            </div>
        </div>`;
}

function renderDashboard(orders) {
    if (!salesDashboard) return;
    salesDashboard.innerHTML = [
        renderChart('Ventas por dia', buildBuckets(orders, 'day')),
        renderChart('Ventas por mes', buildBuckets(orders, 'month')),
        renderChart('Ventas por ano', buildBuckets(orders, 'year')),
    ].join('');
}

async function fetchProducts() {
    const token = getToken();
    const res = await fetch('/api/v1/admin/products', {
        headers: { 'Authorization': `Bearer ${token}` },
    });
    const data = await parseResponse(res);
    if (!res.ok) {
        productsCache = [];
        productsList.innerHTML = `<div class="card">No se pudieron cargar productos: ${data.message || 'sin detalle'}</div>`;
        return;
    }
    productsCache = Array.isArray(data) ? data : [];
    upsertCategoryOptions();
    renderProducts();
    renderLowStockAlerts();
}

function renderProducts() {
    if (!productsCache.length) {
        productsList.innerHTML = '<div class="card">No hay productos.</div>';
        return;
    }
    productsList.innerHTML = productsCache.map(productCard).join('');

    productsList.querySelectorAll('[data-edit]').forEach(btn => {
        btn.addEventListener('click', () => editProduct(Number(btn.getAttribute('data-edit'))));
    });
    productsList.querySelectorAll('[data-delete]').forEach(btn => {
        btn.addEventListener('click', () => deleteProduct(Number(btn.getAttribute('data-delete'))));
    });
}

function editProduct(productId) {
    const product = productsCache.find(item => item.id === productId);
    if (!product) return;
    productForm.product_id.value = product.id;
    productForm.name.value = product.name || '';
    productForm.price.value = product.price || '';
    productForm.stock.value = Number(product.stock || 0);
    productForm.description.value = product.description || '';
    productForm.image_url.value = product.image_url || '';
    productForm.is_available.checked = Boolean(product.is_available);
    categorySelect.value = String(product.category || 'pollos').toLowerCase();
    productMsg.textContent = `Editando producto ID ${product.id}`;
}

async function deleteProduct(productId) {
    if (!confirm(`Eliminar producto ID ${productId}?`)) return;
    const token = getToken();
    const res = await fetch(`/api/v1/products/${productId}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${token}` },
    });
    const data = await parseResponse(res);
    if (!res.ok) {
        productMsg.textContent = data.message || 'No se pudo eliminar';
        return;
    }
    productMsg.textContent = 'Producto eliminado';
    await fetchProducts();
}

async function saveProduct(e) {
    e.preventDefault();
    const token = getToken();

    const customCategory = newCategoryInput.value.trim().toLowerCase();
    const category = customCategory || categorySelect.value;
    if (!category) {
        productMsg.textContent = 'Selecciona o crea una categoria';
        return;
    }

    const stockParsed = Number(productForm.stock.value);
    if (!Number.isFinite(stockParsed) || stockParsed < 0) {
        productMsg.textContent = 'Ingresa un stock valido (0 o mayor).';
        return;
    }

    const payload = {
        name: productForm.name.value.trim(),
        price: Number(productForm.price.value),
        category,
        description: productForm.description.value.trim() || null,
        image_url: productForm.image_url.value.trim() || null,
        stock: Math.round(stockParsed),
        is_available: productForm.is_available.checked,
    };

    const editingId = productForm.product_id.value.trim();
    const url = editingId ? `/api/v1/products/${editingId}` : '/api/v1/products';
    const method = editingId ? 'PUT' : 'POST';

    const res = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify(payload),
    });
    const data = await parseResponse(res);
    if (!res.ok) {
        const validation = data.errors ? Object.values(data.errors).flat().join(' | ') : '';
        productMsg.textContent = validation || data.message || 'No se pudo guardar el producto';
        return;
    }

    productMsg.textContent = editingId ? 'Producto actualizado' : 'Producto creado';
    clearProductForm();
    await fetchProducts();
}

async function fetchOrders() {
    const token = getToken();
    const params = new URLSearchParams();
    if (filterStatus.value) params.set('status', filterStatus.value);
    if (filterPaymentStatus.value) params.set('payment_status', filterPaymentStatus.value);
    if (filterDateFrom.value) params.set('date_from', filterDateFrom.value);
    if (filterDateTo.value) params.set('date_to', filterDateTo.value);
    const query = params.toString() ? `?${params.toString()}` : '';

    const res = await fetch(`/api/v1/admin/orders${query}`, {
        headers: { 'Authorization': `Bearer ${token}` },
    });
    const data = await parseResponse(res);
    if (!res.ok) {
        renderDashboard([]);
        ordersList.innerHTML = '<div class="card">No se pudieron cargar pedidos.</div>';
        return;
    }

    const orders = data.data || [];
    renderDashboard(orders);
    if (!orders.length) {
        ordersList.innerHTML = '<div class="card">Sin pedidos recientes.</div>';
        return;
    }

    ordersList.innerHTML = orders.map(order => `
        <article class="card">
            <div class="card-top">
                <strong>${order.tracking_code}</strong>
                <span class="tag">${statusEs(order.status)}</span>
            </div>
            <div class="muted">ID: ${order.id} | ${order.customer_name}</div>
            <div class="muted">Fecha/Hora: ${new Date(order.created_at).toLocaleString()}</div>
            <div class="muted">Pago: ${order.payment_method || 'n/a'} (${paymentStatusEs(order.payment_status)})</div>
            <div class="muted">Operacion: ${order.payment_reference || 'sin codigo'}</div>
            <div style="margin-top:6px;">Total: <strong>S/ ${Number(order.total_amount).toFixed(2)}</strong></div>
            <div style="display:flex; gap:8px; margin-top:8px;">
                <button data-fill="${order.id}">Usar en actualizar estado</button>
                <button data-delete-order="${order.id}" style="border-color:#ffc1b5; color:#a53216;">Eliminar pedido</button>
            </div>
        </article>
    `).join('');

    ordersList.querySelectorAll('[data-fill]').forEach(btn => {
        btn.addEventListener('click', () => {
            statusForm.order_id.value = btn.getAttribute('data-fill');
            paymentForm.order_id.value = btn.getAttribute('data-fill');
            statusMsg.textContent = `Pedido ID ${btn.getAttribute('data-fill')} seleccionado`;
            paymentMsg.textContent = `Pedido ID ${btn.getAttribute('data-fill')} seleccionado`;
        });
    });
    ordersList.querySelectorAll('[data-delete-order]').forEach(btn => {
        btn.addEventListener('click', () => deleteOrder(Number(btn.getAttribute('data-delete-order'))));
    });
}

async function exportCsv() {
    const token = getToken();
    const params = new URLSearchParams();
    if (filterStatus.value) params.set('status', filterStatus.value);
    if (filterPaymentStatus.value) params.set('payment_status', filterPaymentStatus.value);
    if (filterDateFrom.value) params.set('date_from', filterDateFrom.value);
    if (filterDateTo.value) params.set('date_to', filterDateTo.value);
    const query = params.toString() ? `?${params.toString()}` : '';

    const res = await fetch(`/api/v1/admin/orders/export${query}`, {
        headers: { 'Authorization': `Bearer ${token}` },
    });
    if (!res.ok) {
        statusMsg.textContent = 'No se pudo exportar CSV';
        return;
    }
    const blob = await res.blob();
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'pedidos-admin.csv';
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
}

async function fetchUsers() {
    const token = getToken();
    const res = await fetch('/api/v1/admin/users', {
        headers: { 'Authorization': `Bearer ${token}` },
    });
    const data = await parseResponse(res);
    if (!res.ok) {
        usersList.innerHTML = '<div class="card">No se pudieron cargar usuarios.</div>';
        return;
    }

    const users = data.data || [];
    if (!users.length) {
        usersList.innerHTML = '<div class="card">Sin cuentas registradas.</div>';
        return;
    }

    usersList.innerHTML = users.map(user => {
        const days = Math.floor((Date.now() - new Date(user.created_at).getTime()) / (1000 * 60 * 60 * 24));
        return `
            <article class="card">
                <div class="card-top">
                    <strong>${user.name}</strong>
                    <span class="tag">${user.role}</span>
                </div>
                <div class="muted">${user.email}</div>
                <div class="muted">Telefono: ${user.phone || '-'}</div>
                <div class="muted">Creada: ${new Date(user.created_at).toLocaleString()} (${days} dias)</div>
                <div class="muted">Estado: ${user.is_active ? 'Activa' : 'Desactivada'}</div>
                <div style="display:flex; gap:8px; margin-top:8px;">
                    <button data-toggle-user="${user.id}" data-next="${user.is_active ? '0' : '1'}">
                        ${user.is_active ? 'Dar de baja' : 'Reactivar'}
                    </button>
                    <button data-delete-user="${user.id}" style="border-color:#ffc1b5; color:#a53216;">Eliminar</button>
                </div>
            </article>`;
    }).join('');

    usersList.querySelectorAll('[data-toggle-user]').forEach(btn => {
        btn.addEventListener('click', () => toggleUserActive(
            Number(btn.getAttribute('data-toggle-user')),
            btn.getAttribute('data-next') === '1'
        ));
    });

    usersList.querySelectorAll('[data-delete-user]').forEach(btn => {
        btn.addEventListener('click', () => deleteUser(Number(btn.getAttribute('data-delete-user'))));
    });
}

async function toggleUserActive(userId, isActive) {
    const token = getToken();
    const res = await fetch(`/api/v1/admin/users/${userId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({ is_active: isActive }),
    });
    const data = await parseResponse(res);
    if (!res.ok) {
        statusMsg.textContent = data.message || 'No se pudo actualizar cuenta';
        return;
    }
    statusMsg.textContent = `Cuenta ${data.name} ${data.is_active ? 'activada' : 'desactivada'}`;
    await fetchUsers();
}

async function deleteUser(userId) {
    if (!confirm(`Eliminar usuario ID ${userId}?`)) return;
    const token = getToken();
    const res = await fetch(`/api/v1/admin/users/${userId}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${token}` },
    });
    const data = await parseResponse(res);
    if (!res.ok) {
        statusMsg.textContent = data.message || 'No se pudo eliminar usuario';
        return;
    }
    statusMsg.textContent = `Usuario ${userId} eliminado`;
    await fetchUsers();
}

async function updateOrderStatus(e) {
    e.preventDefault();
    const token = getToken();
    const orderId = statusForm.order_id.value.trim();
    if (!orderId) return;

    const payload = {
        status: statusForm.status.value,
        note: statusForm.note.value.trim() || null,
    };

    const res = await fetch(`/api/v1/admin/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify(payload),
    });
    const data = await parseResponse(res);
    if (!res.ok) {
        statusMsg.textContent = data.message || 'No se pudo actualizar estado';
        return;
    }
    statusMsg.textContent = `Estado actualizado a ${statusEs(data.status)}`;
    await fetchOrders();
}

async function updatePaymentStatus(e) {
    e.preventDefault();
    const token = getToken();
    const orderId = paymentForm.order_id.value.trim();
    if (!orderId) return;

    const payload = {
        payment_status: paymentForm.payment_status.value,
        payment_reference: paymentForm.payment_reference.value.trim() || null,
        note: paymentForm.note.value.trim() || null,
    };

    const res = await fetch(`/api/v1/admin/orders/${orderId}/payment-status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify(payload),
    });
    const data = await parseResponse(res);
    if (!res.ok) {
        paymentMsg.textContent = data.message || 'No se pudo actualizar pago';
        return;
    }
    paymentMsg.textContent = `Pago actualizado a ${paymentStatusEs(data.payment_status)}`;
    await fetchOrders();
}

async function deleteOrder(orderId) {
    if (!confirm(`Eliminar pedido ID ${orderId}? Esta accion lo quitara de la vista del cliente.`)) return;
    const token = getToken();
    const res = await fetch(`/api/v1/admin/orders/${orderId}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${token}` },
    });
    const data = await parseResponse(res);
    if (!res.ok) {
        statusMsg.textContent = data.message || 'No se pudo eliminar pedido';
        return;
    }
    statusMsg.textContent = `Pedido ${orderId} eliminado`;
    paymentMsg.textContent = `Pedido ${orderId} eliminado`;
    await fetchOrders();
}

async function boot() {
    const user = getUser();
    const session = parseSession();
    if (!canUseAdmin() || !session || session.role !== 'admin' || Date.now() > Number(session.expiresAt || 0)) {
        clearAuth();
        denyBox.style.display = 'block';
        adminUserLabel.textContent = 'Sin permisos admin';
        setTimeout(() => { window.location.href = '/admin/login'; }, 800);
        return;
    }

    try {
        const meRes = await fetch('/api/v1/auth/me', {
            headers: { 'Authorization': `Bearer ${getToken()}` },
        });
        const meData = await meRes.json();
        if (!meRes.ok || !meData.user || meData.user.role !== 'admin' || !meData.user.is_active) {
            clearAuth();
            window.location.href = '/admin/login';
            return;
        }
        localStorage.setItem('ed_user', JSON.stringify(meData.user));
    } catch {
        clearAuth();
        window.location.href = '/admin/login';
        return;
    }

    touchAdminSession();
    adminUserLabel.textContent = `Admin: ${(getUser() || user).name}`;
    adminContent.style.display = 'grid';

    upsertCategoryOptions();
    await fetchProducts();
    await fetchOrders();
    await fetchUsers();

    startAutoRefresh();
}

function startAutoRefresh() {
    if (refreshTimer) clearInterval(refreshTimer);
    const settings = getAdminSettings();
    const everyMs = Math.max(10, Number(settings.refreshSeconds || 20)) * 1000;
    refreshTimer = setInterval(async () => {
        if (Date.now() > Number((parseSession() || {}).expiresAt || 0)) {
            clearAuth();
            window.location.href = '/admin/login';
            return;
        }
        await fetchProducts();
        await fetchOrders();
        await fetchUsers();
    }, everyMs);
}

cancelEditBtn.addEventListener('click', clearProductForm);
productForm.addEventListener('submit', saveProduct);
statusForm.addEventListener('submit', updateOrderStatus);
paymentForm.addEventListener('submit', updatePaymentStatus);
applyFiltersBtn.addEventListener('click', fetchOrders);
clearFiltersBtn.addEventListener('click', () => {
    filterStatus.value = '';
    filterPaymentStatus.value = '';
    filterDateFrom.value = '';
    filterDateTo.value = '';
    fetchOrders();
});
exportCsvBtn.addEventListener('click', exportCsv);
saveAdminSettingsBtn.addEventListener('click', saveAdminSettings);
adminLogoutBtn.addEventListener('click', () => {
    clearAuth();
    window.location.href = '/admin/login';
});
['click', 'keydown', 'mousemove', 'touchstart', 'scroll'].forEach(evt => {
    window.addEventListener(evt, touchAdminSession, { passive: true });
});

applyAdminSettingsToForm();
boot();
</script>
</body>
</html>
