/**
 * Draggable seating canvas for admin TableGrid (Livewire).
 * Persists center position via updatePosition (position_x / position_y as %).
 */

const SNAP_PCT = 1.5;

function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
}

function snap(value) {
    if (SNAP_PCT <= 0) return value;
    return Math.round(value / SNAP_PCT) * SNAP_PCT;
}

export function findLivewireComponent(el) {
    if (typeof window.Livewire === 'undefined') return null;
    let cur = el;
    while (cur) {
        const id = cur.getAttribute?.('wire:id');
        if (id) {
            const c = window.Livewire.find(id);
            if (c) return c;
        }
        cur = cur.parentElement;
    }
    return null;
}

const DRAG_THRESHOLD_PX = 6;

function startDrag(canvas, node, component, startEvent) {
    const canvasRect = canvas.getBoundingClientRect();
    const w = canvasRect.width;
    const h = canvasRect.height;
    if (w <= 0 || h <= 0) return;

    const originalStyle = node.getAttribute('style') ?? '';

    const nodeRect = node.getBoundingClientRect();
    const leftPx = nodeRect.left - canvasRect.left;
    const topPx = nodeRect.top - canvasRect.top;
    node.style.left = `${leftPx}px`;
    node.style.top = `${topPx}px`;
    node.style.transform = 'none';

    const startLeft = node.offsetLeft;
    const startTop = node.offsetTop;
    const startX = startEvent.clientX;
    const startY = startEvent.clientY;

    let moved = false;

    node.classList.add('seating-table--dragging');
    document.body.style.cursor = 'grabbing';
    document.body.style.userSelect = 'none';

    const onMove = (ev) => {
        const dx = ev.clientX - startX;
        const dy = ev.clientY - startY;
        if (Math.abs(dx) > DRAG_THRESHOLD_PX || Math.abs(dy) > DRAG_THRESHOLD_PX) {
            moved = true;
        }
        let nx = startLeft + dx;
        let ny = startTop + dy;

        const nw = node.offsetWidth;
        const nh = node.offsetHeight;
        nx = clamp(nx, 0, w - nw);
        ny = clamp(ny, 0, h - nh);

        node.style.left = `${nx}px`;
        node.style.top = `${ny}px`;
    };

    const onUp = () => {
        document.removeEventListener('pointermove', onMove);
        document.removeEventListener('pointerup', onUp);
        document.removeEventListener('pointercancel', onUp);
        node.classList.remove('seating-table--dragging');
        document.body.style.cursor = '';
        document.body.style.userSelect = '';

        const id = parseInt(node.dataset.tableId, 10);
        if (!Number.isFinite(id)) return;

        if (!moved) {
            node.setAttribute('style', originalStyle);
            component.call('selectTable', id);
            return;
        }

        const r = canvas.getBoundingClientRect();
        const cw = r.width;
        const ch = r.height;
        const left = node.offsetLeft;
        const top = node.offsetTop;
        const nw = node.offsetWidth;
        const nh = node.offsetHeight;

        const cxPx = left + nw / 2;
        const cyPx = top + nh / 2;
        let xPct = (cxPx / cw) * 100;
        let yPct = (cyPx / ch) * 100;
        xPct = snap(clamp(xPct, 0, 100));
        yPct = snap(clamp(yPct, 0, 100));

        component.call('updatePosition', id, xPct, yPct);
    };

    document.addEventListener('pointermove', onMove);
    document.addEventListener('pointerup', onUp);
    document.addEventListener('pointercancel', onUp);
}

function onPointerDown(e) {
    const canvas = e.target.closest?.('[data-seating-canvas]');
    if (!canvas) return;
    if (canvas.dataset.previewOnly === 'true') return;

    const handle = e.target.closest?.('[data-seating-drag-handle]');
    if (!handle) return;

    const node = handle.closest?.('[data-seating-table]');
    if (!node || !canvas.contains(node)) return;
    if (e.button !== 0) return;

    const tag = (e.target && e.target.tagName) || '';
    if (tag === 'SELECT' || tag === 'OPTION' || e.target.closest('select')) return;
    if (e.target.closest('button')) return;

    e.preventDefault();

    const component = findLivewireComponent(canvas);
    if (!component) return;

    startDrag(canvas, node, component, e);
}

let delegated = false;

function ensureDelegatedListener() {
    if (delegated) return;
    delegated = true;
    document.addEventListener('pointerdown', onPointerDown);
}

document.addEventListener('livewire:init', () => {
    ensureDelegatedListener();
});

if (typeof window.Livewire !== 'undefined') {
    ensureDelegatedListener();
}

/** @deprecated kept for tests / manual calls */
export function initSeatingChartEditor() {
    ensureDelegatedListener();
}
