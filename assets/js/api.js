// assets/js/api.js
// Thin fetch wrapper around app/public/*.php JSON endpoints.

const API_BASE = url('/app/public');

async function api(endpoint, method = 'GET', body = null) {
    const options = {
        method,
        headers: { 'Content-Type': 'application/json' },
    };

    if (body) options.body = JSON.stringify(body);

    const res  = await fetch(`${API_BASE}/${endpoint}`, options);
    const json = await res.json();

    if (!json.success) throw new Error(json.message || 'Something went wrong.');

    return json;
}

async function apiUpload(endpoint, formData) {
    const res  = await fetch(`${API_BASE}/${endpoint}`, { method: 'POST', body: formData });
    const json = await res.json();

    if (!json.success) throw new Error(json.message || 'Upload failed.');

    return json;
}

function toast(message, isError = false) {
    const box = document.getElementById('toast-box');
    if (!box) return;

    const el = document.createElement('div');
    el.className = `px-4 py-3 rounded-lg shadow-lg text-sm font-medium text-white ${isError ? 'bg-red-500' : 'bg-navy'} animate-[fadeIn_.2s_ease-out]`;
    el.textContent = message;

    box.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

function formToObject(form) {
    return Object.fromEntries(new FormData(form).entries());
}
