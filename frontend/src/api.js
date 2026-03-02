import { API_BASE, assetUrl } from './config.js';

function jsonHeaders(token) {
  const headers = { 'Content-Type': 'application/json' };
  if (token) headers.Authorization = `Bearer ${token}`;
  return headers;
}

async function parseJson(response) {
  const data = await response.json().catch(() => ({}));
  if (!response.ok) {
    const message = data.message
      || data.error
      || Object.values(data.errors || {}).flat()[0]
      || 'Error en la solicitud';
    throw new Error(message);
  }
  return data;
}

function mapProduct(product) {
  return {
    ...product,
    image_url: assetUrl(product.image_url),
  };
}

export async function getProducts() {
  const res = await fetch(`${API_BASE}/products`);
  const data = await parseJson(res);
  const list = Array.isArray(data) ? data : [];
  return list.map(mapProduct);
}

export async function register(payload) {
  const res = await fetch(`${API_BASE}/auth/register`, {
    method: 'POST',
    headers: jsonHeaders(),
    body: JSON.stringify(payload),
  });
  return parseJson(res);
}

export async function login(payload) {
  const res = await fetch(`${API_BASE}/auth/login`, {
    method: 'POST',
    headers: jsonHeaders(),
    body: JSON.stringify(payload),
  });
  return parseJson(res);
}

export async function me(token) {
  const res = await fetch(`${API_BASE}/auth/me`, {
    headers: jsonHeaders(token),
  });
  return parseJson(res);
}

export async function createOrder(token, payload) {
  const res = await fetch(`${API_BASE}/orders`, {
    method: 'POST',
    headers: jsonHeaders(token),
    body: JSON.stringify(payload),
  });
  return parseJson(res);
}

export async function getMyOrders(token) {
  const res = await fetch(`${API_BASE}/orders/my`, {
    headers: jsonHeaders(token),
  });
  const data = await parseJson(res);
  return Array.isArray(data) ? data : [];
}

export async function trackOrder(code) {
  const res = await fetch(`${API_BASE}/orders/track/${encodeURIComponent(code)}`);
  return parseJson(res);
}

export async function downloadReceipt(token, orderId) {
  const res = await fetch(`${API_BASE}/orders/${orderId}/receipt`, {
    headers: token ? { Authorization: `Bearer ${token}` } : {},
  });
  if (!res.ok) {
    throw new Error('No se pudo descargar el ticket');
  }
  return res.blob();
}

export async function uploadPaymentProof(token, orderId, file, paymentReference = '') {
  const formData = new FormData();
  formData.append('proof', file);
  if (paymentReference) formData.append('payment_reference', paymentReference);

  const res = await fetch(`${API_BASE}/orders/${orderId}/payment-proof`, {
    method: 'POST',
    headers: token ? { Authorization: `Bearer ${token}` } : {},
    body: formData,
  });
  return parseJson(res);
}

export async function getAdminOrders(token) {
  const res = await fetch(`${API_BASE}/admin/orders`, {
    headers: jsonHeaders(token),
  });
  return parseJson(res);
}

export async function updateAdminOrderStatus(token, orderId, payload) {
  const res = await fetch(`${API_BASE}/admin/orders/${orderId}/status`, {
    method: 'PATCH',
    headers: jsonHeaders(token),
    body: JSON.stringify(payload),
  });
  return parseJson(res);
}

export async function updateAdminPaymentStatus(token, orderId, payload) {
  const res = await fetch(`${API_BASE}/admin/orders/${orderId}/payment-status`, {
    method: 'PATCH',
    headers: jsonHeaders(token),
    body: JSON.stringify(payload),
  });
  return parseJson(res);
}

export async function deleteAdminOrder(token, orderId) {
  const res = await fetch(`${API_BASE}/admin/orders/${orderId}`, {
    method: 'DELETE',
    headers: jsonHeaders(token),
  });
  return parseJson(res);
}

export async function createProduct(token, payload) {
  const res = await fetch(`${API_BASE}/products`, {
    method: 'POST',
    headers: jsonHeaders(token),
    body: JSON.stringify(payload),
  });
  return parseJson(res);
}

export async function deleteProduct(token, productId) {
  const res = await fetch(`${API_BASE}/products/${productId}`, {
    method: 'DELETE',
    headers: jsonHeaders(token),
  });
  return parseJson(res);
}

export async function getAdminUsers(token) {
  const res = await fetch(`${API_BASE}/admin/users`, {
    headers: jsonHeaders(token),
  });
  return parseJson(res);
}

export async function updateAdminUser(token, userId, payload) {
  const res = await fetch(`${API_BASE}/admin/users/${userId}`, {
    method: 'PATCH',
    headers: jsonHeaders(token),
    body: JSON.stringify(payload),
  });
  return parseJson(res);
}

export async function deleteAdminUser(token, userId) {
  const res = await fetch(`${API_BASE}/admin/users/${userId}`, {
    method: 'DELETE',
    headers: jsonHeaders(token),
  });
  return parseJson(res);
}
