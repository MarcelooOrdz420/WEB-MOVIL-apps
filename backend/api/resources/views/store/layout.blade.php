<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="/images/ico-pollo.jpg">
    <link rel="shortcut icon" type="image/jpeg" href="/images/ico-pollo.jpg">
    <title>@yield('title', 'Pollos y Parrillas El Dorado')</title>
    <style>
        :root {
            --black: #0f0f10;
            --orange: #ff6f1f;
            --orange-soft: #ff9d5a;
            --orange-deep: #f25d00;
            --line: #2a2a2c;
            --cream: #fff8f2;
            --white: #ffffff;
            --text-dark: #24160f;
            --text-muted: #68432e;
            --shadow-soft: 0 12px 32px rgba(30, 12, 3, .08);
            --shadow-card: 0 14px 28px rgba(255, 111, 31, .12);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Trebuchet MS", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at top, #2a180d 0%, #121213 38%, #0f0f10 100%);
            color: #fff;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: rgba(13, 13, 14, 0.95);
            border-bottom: 1px solid var(--line);
            backdrop-filter: blur(6px);
        }

        .container { max-width: 1150px; margin: 0 auto; padding: 0 16px; }

        .topbar-inner {
            min-height: 72px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .left-nav {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .brand {
            font-weight: 900;
            color: var(--orange);
            letter-spacing: 0.4px;
            margin-right: 8px;
        }

        .left-nav a {
            text-decoration: none;
            color: #f2f2f2;
            border: 1px solid #313131;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 13px;
            background: #171718;
            transition: .2s ease;
        }

        .left-nav a.active {
            border-color: var(--orange);
            color: #331707;
            background: linear-gradient(120deg, var(--orange), var(--orange-soft));
            font-weight: 800;
        }

        .left-nav a:hover {
            border-color: var(--orange-soft);
            transform: translateY(-1px);
        }

        .right-tools {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-name {
            font-size: 14px;
            color: #ffd2b2;
            font-weight: 700;
            white-space: nowrap;
        }

        .cart-link {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #1b1b1c;
            border: 1px solid #343437;
            text-decoration: none;
            position: relative;
            transition: .2s ease;
        }

        .cart-link:hover {
            border-color: var(--orange);
            transform: translateY(-1px);
        }

        .cart-count {
            position: absolute;
            right: -2px;
            top: -2px;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            background: var(--orange);
            color: #2c1406;
            font-size: 11px;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        .logout-btn, .login-btn {
            border: 1px solid #343437;
            border-radius: 999px;
            background: #1b1b1c;
            color: #f0f0f0;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 10px;
            cursor: pointer;
            text-decoration: none;
        }

        .logout-btn:hover, .login-btn:hover { border-color: var(--orange); }

        main.page {
            background: var(--cream);
            color: var(--text-dark);
            min-height: calc(100vh - 72px);
            padding: 24px 0 30px;
        }

        .panel {
            background: var(--white);
            border: 1px solid #ffd7bd;
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 12px;
            box-shadow: var(--shadow-soft);
        }

        img {
            max-width: 100%;
            display: block;
        }

        .title {
            margin-top: 0;
            margin-bottom: 14px;
            color: #311a0f;
            font-size: 30px;
        }

        .section-title {
            margin: 0 0 12px;
            color: #311a0f;
            font-size: 24px;
        }

        .grid-auto {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .btn-main {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            cursor: pointer;
            font-weight: 800;
            color: #2c1506;
            background: linear-gradient(120deg, var(--orange), var(--orange-soft));
            box-shadow: 0 8px 18px rgba(255, 111, 31, .3);
            transition: .2s ease;
        }

        .btn-main:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(255, 111, 31, .36);
        }

        .btn-soft {
            border: 1px solid #f2c6a4;
            border-radius: 10px;
            padding: 9px 11px;
            cursor: pointer;
            font-weight: 700;
            color: #7e3900;
            background: #fff0e4;
            transition: .2s ease;
        }

        .btn-soft:hover { border-color: var(--orange); }

        .input-main,
        .select-main,
        .textarea-main {
            width: 100%;
            border: 1px solid #edc8a8;
            border-radius: 10px;
            padding: 10px;
            background: #fffefc;
            color: #28170e;
        }

        .label-main {
            font-size: 13px;
            font-weight: 700;
            color: #55270a;
            display: block;
            margin-bottom: 5px;
        }

        .product-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, #fff 0%, #fff8f1 100%);
            border: 1px solid #ffd2ad;
            border-radius: 16px;
            padding: 12px;
            box-shadow: var(--shadow-card);
            display: grid;
            gap: 8px;
            transition: transform .26s ease, box-shadow .26s ease, border-color .26s ease;
            isolation: isolate;
        }

        .product-card::after {
            content: "";
            position: absolute;
            inset: -40% auto auto -30%;
            width: 90px;
            height: 220px;
            background: linear-gradient(180deg, rgba(255,255,255,0), rgba(255,255,255,.42), rgba(255,255,255,0));
            transform: rotate(24deg);
            opacity: 0;
            pointer-events: none;
            transition: opacity .22s ease, transform .55s ease;
            z-index: 0;
        }

        .product-image-wrap {
            aspect-ratio: 4 / 3;
            max-height: 180px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(120deg, #ffe9d7, #fff7f0);
            border: 1px solid rgba(255, 190, 148, .7);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image {
            width: 100%;
            height: 100%;
            max-height: 180px;
            object-fit: cover;
            object-position: center;
            display: block;
            transition: transform .35s ease, filter .35s ease;
            transform-origin: center;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 34px rgba(255, 111, 31, .18);
            border-color: #ffbf92;
        }

        .product-card:hover::after {
            opacity: 1;
            transform: translateX(320px) rotate(24deg);
        }

        .product-card:hover .product-image {
            transform: scale(1.09);
            filter: saturate(1.06) contrast(1.03);
        }

        .muted-main {
            color: var(--text-muted);
            font-size: 13px;
            margin: 0;
        }

        @media (max-width: 900px) {
            .topbar-inner {
                flex-direction: column;
                align-items: stretch;
                padding: 10px 0;
            }

            .right-tools {
                justify-content: flex-end;
            }
        }

        @media (max-width: 640px) {
            .title {
                font-size: 26px;
            }

            .product-image-wrap,
            .product-image {
                max-height: 150px;
            }

            .left-nav {
                gap: 6px;
            }

            .left-nav a {
                font-size: 12px;
                padding: 7px 10px;
            }
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <nav class="left-nav">
            <span class="brand">Pollos y Parrillas "El Dorado"</span>
            <a href="{{ route('store.products') }}" class="{{ request()->routeIs('store.products') ? 'active' : '' }}">Productos</a>
            <a href="{{ route('store.about') }}" class="{{ request()->routeIs('store.about') ? 'active' : '' }}">Quienes somos</a>
            <a href="{{ route('store.location') }}" class="{{ request()->routeIs('store.location') ? 'active' : '' }}">Ubicacion</a>
            <a href="{{ route('store.experts') }}" class="{{ request()->routeIs('store.experts') ? 'active' : '' }}">Expertos</a>
            <a href="{{ route('store.orders') }}" class="{{ request()->routeIs('store.orders') ? 'active' : '' }}">Mis pedidos</a>
        </nav>
        <div class="right-tools">
            <span id="sessionUserName" class="user-name">Invitado</span>
            <a id="clientLoginBtn" class="login-btn" href="/login">Login</a>
            <button id="clientLogoutBtn" class="logout-btn" type="button">Salir</button>
            <a class="cart-link" href="{{ route('store.cart') }}" aria-label="Ir al carrito">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M7 5h14l-1.5 8.5H9L7 5Z" stroke="#ff9d5a" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="M7 5 6.2 3H3" stroke="#ff9d5a" stroke-width="1.8" stroke-linecap="round"/>
                    <circle cx="10" cy="19" r="1.6" stroke="#ff9d5a" stroke-width="1.6"/>
                    <circle cx="18" cy="19" r="1.6" stroke="#ff9d5a" stroke-width="1.6"/>
                </svg>
                <span id="cartCountBadge" class="cart-count">0</span>
            </a>
        </div>
    </div>
</header>

<main class="page">
    <div class="container">
        @yield('content')
    </div>
</main>

<script>
const userNameEl = document.getElementById('sessionUserName');
const cartCountEl = document.getElementById('cartCountBadge');
const clientLoginBtn = document.getElementById('clientLoginBtn');
const clientLogoutBtn = document.getElementById('clientLogoutBtn');
const CLIENT_TIMEOUT_MS = 60 * 60 * 1000;

function parseUser() {
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

function clearClientSession() {
    localStorage.removeItem('ed_token');
    localStorage.removeItem('ed_user');
    localStorage.removeItem('ed_session');
    localStorage.removeItem('ed_cart');
    localStorage.removeItem('ed_last_tracking');
    localStorage.removeItem('ed_recent_trackings');
}

async function validateSessionWithServer() {
    const token = localStorage.getItem('ed_token');
    if (!token) return false;
    try {
        const res = await fetch('/api/v1/auth/me', {
            headers: { 'Authorization': `Bearer ${token}` },
        });
        const data = await res.json();
        if (!res.ok || !data.user || !data.user.is_active) return false;
        localStorage.setItem('ed_user', JSON.stringify(data.user));
        return true;
    } catch {
        return false;
    }
}

function touchSessionActivity() {
    const session = parseSession();
    if (!session) return;
    session.lastActivity = Date.now();
    session.expiresAt = Date.now() + CLIENT_TIMEOUT_MS;
    saveSession(session);
}

async function initClientSession() {
    const token = localStorage.getItem('ed_token');
    const user = parseUser();
    if (!token || !user) {
        clearClientSession();
        updateTopBar();
        return;
    }

    let session = parseSession();
    if (!session || session.role !== (user.role || 'customer')) {
        session = { role: user.role || 'customer', lastActivity: Date.now(), expiresAt: Date.now() + CLIENT_TIMEOUT_MS };
        saveSession(session);
    }

    if (Date.now() > Number(session.expiresAt || 0)) {
        clearClientSession();
        updateTopBar();
        if (!window.location.pathname.startsWith('/login')) window.location.href = '/login';
        return;
    }

    const valid = await validateSessionWithServer();
    if (!valid) {
        clearClientSession();
        updateTopBar();
        if (!window.location.pathname.startsWith('/login')) window.location.href = '/login';
        return;
    }

    touchSessionActivity();
    updateTopBar();
}

function updateTopBar() {
    const user = parseUser();
    userNameEl.textContent = user ? user.name : 'Invitado';
    clientLogoutBtn.style.display = user ? 'inline-block' : 'none';
    clientLoginBtn.style.display = user ? 'none' : 'inline-block';

    const cart = JSON.parse(localStorage.getItem('ed_cart') || '[]');
    const count = cart.reduce((acc, item) => acc + Number(item.qty || 0), 0);
    cartCountEl.textContent = count;
}

window.addEventListener('storage', updateTopBar);
clientLogoutBtn.addEventListener('click', () => {
    clearClientSession();
    updateTopBar();
    window.location.href = '/login';
});
['click', 'keydown', 'mousemove', 'touchstart', 'scroll'].forEach(evt => {
    window.addEventListener(evt, touchSessionActivity, { passive: true });
});

setInterval(() => {
    const session = parseSession();
    if (!session) return;
    if (Date.now() > Number(session.expiresAt || 0)) {
        clearClientSession();
        updateTopBar();
        window.location.href = '/login';
    }
}, 15000);

initClientSession();
</script>
@yield('scripts')
</body>
</html>
