// assets/js/api.js
// Thin fetch wrapper around app/public/*.php JSON endpoints.

const API_BASE = url("/app/public");

async function api(endpoint, method = "GET", body = null) {
  const options = {
    method,
    headers: { "Content-Type": "application/json" },
  };

  if (body) options.body = JSON.stringify(body);

  const res = await fetch(`${API_BASE}/${endpoint}`, options);
  const json = await res.json();

  if (!json.success) throw new Error(json.message || "Something went wrong.");

  return json;
}

function toast(message, isError = false) {
  const box = document.getElementById("toast-box");
  if (!box) return;

  const el = document.createElement("div");
  el.className = `flex items-center gap-2 px-4 py-3 rounded-lg shadow-lg text-sm font-medium text-white ${
    isError ? "bg-red-500" : "bg-green-600"
  } animate-[fadeIn_.2s_ease-out]`;

  const icon = document.createElement("i");
  icon.className = `${
    isError ? "ri-error-warning-line" : "ri-checkbox-circle-line"
  } text-base flex-shrink-0`;

  const text = document.createElement("span");
  text.textContent = message;

  el.append(icon, text);
  box.appendChild(el);
  setTimeout(() => el.remove(), 3500);
}

function formToObject(form) {
  return Object.fromEntries(new FormData(form).entries());
}
