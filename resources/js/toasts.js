/**
 * Fixed toasts — session flash + Livewire dispatch('notify', type: ..., message: ...).
 */

const styles = {
    success:
        'border border-emerald-200/90 bg-emerald-50 text-emerald-900 shadow-lg shadow-emerald-900/5',
    error: 'border border-red-200/90 bg-red-50 text-red-900 shadow-lg shadow-red-900/5',
    warning:
        'border border-amber-200/90 bg-amber-50 text-amber-950 shadow-lg shadow-amber-900/5',
    info: 'border border-sky-200/90 bg-sky-50 text-sky-950 shadow-lg shadow-sky-900/5',
};

function normalizeType(t) {
    const k = String(t || 'success').toLowerCase();
    if (['success', 'error', 'warning', 'info'].includes(k)) return k;
    return 'info';
}

function parseNotifyDetail(detail) {
    if (detail == null) return { type: 'success', message: '' };
    if (typeof detail === 'string') {
        return { type: 'success', message: detail };
    }
    if (Array.isArray(detail)) {
        const flat = detail.flat();
        if (flat.length === 1 && typeof flat[0] === 'string') {
            return { type: 'success', message: flat[0] };
        }
        return { type: 'success', message: JSON.stringify(detail) };
    }
    if (typeof detail === 'object') {
        return {
            type: normalizeType(detail.type),
            message: String(detail.message ?? detail.body ?? ''),
        };
    }
    return { type: 'success', message: String(detail) };
}

export function showToast(type, message, durationMs = 5200) {
    const kind = normalizeType(type);
    const text = String(message || '').trim();
    if (!text) return;

    let root = document.getElementById('toast-root');
    if (!root) {
        root = document.createElement('div');
        root.id = 'toast-root';
        root.setAttribute('aria-live', 'polite');
        root.className =
            'pointer-events-none fixed inset-x-0 top-0 z-[200] flex flex-col items-stretch gap-2 p-4 sm:items-end sm:p-5';
        document.body.appendChild(root);
    }

    const wrap = document.createElement('div');
    wrap.className =
        'pointer-events-auto flex max-w-md items-start gap-3 rounded-xl px-4 py-3 text-sm leading-snug sm:ml-auto';
    wrap.className += ' ' + (styles[kind] || styles.info);
    wrap.setAttribute('role', 'status');

    const msg = document.createElement('p');
    msg.className = 'min-w-0 flex-1';
    msg.textContent = text;

    const close = document.createElement('button');
    close.type = 'button';
    close.className =
        'shrink-0 rounded-lg p-1 text-current opacity-60 hover:bg-black/5 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-[#0082cd]/40';
    close.setAttribute('aria-label', 'Dismiss');
    close.innerHTML =
        '<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>';

    let timer = setTimeout(remove, durationMs);

    function remove() {
        clearTimeout(timer);
        wrap.classList.add('opacity-0', 'translate-x-2', 'transition-all', 'duration-200');
        setTimeout(() => wrap.remove(), 200);
    }

    close.addEventListener('click', remove);
    wrap.appendChild(msg);
    wrap.appendChild(close);
    root.appendChild(wrap);
}

export function initToasts() {
    const el = document.getElementById('app-flash-data');
    if (!el) return;
    try {
        const d = JSON.parse(el.textContent || '{}');
        if (d.success) showToast('success', d.success);
        if (d.error) showToast('error', d.error);
        if (d.warning) showToast('warning', d.warning);
        if (d.info) showToast('info', d.info);
    } catch (_) {
        /* ignore */
    }
}

export function registerLivewireNotify() {
    document.addEventListener('livewire:init', () => {
        if (typeof window.Livewire === 'undefined' || typeof window.Livewire.on !== 'function') {
            return;
        }
        window.Livewire.on('notify', (detail) => {
            const { type, message } = parseNotifyDetail(detail);
            if (message) showToast(type, message);
        });
    });
}
