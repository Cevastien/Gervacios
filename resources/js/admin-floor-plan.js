/**
 * Floor plan: grid guide overlay (localStorage) + reapply after Livewire morph.
 */
const GRID_KEY = 'admin_floor_grid_guides';

function applyGridFromStorage() {
    const canvas = document.querySelector('[data-seating-canvas]:not([data-preview-only="true"])');
    if (!canvas) {
        return;
    }
    try {
        if (localStorage.getItem(GRID_KEY) === '1') {
            canvas.classList.add('seating-canvas--guides');
        } else {
            canvas.classList.remove('seating-canvas--guides');
        }
    } catch {
        // ignore
    }
}

function onGridToggleClick(e) {
    const btn = e.target.closest?.('[data-floor-grid-toggle]');
    if (!btn) {
        return;
    }
    const canvas = document.querySelector('[data-seating-canvas]:not([data-preview-only="true"])');
    if (!canvas) {
        return;
    }
    e.preventDefault();
    canvas.classList.toggle('seating-canvas--guides');
    try {
        localStorage.setItem(GRID_KEY, canvas.classList.contains('seating-canvas--guides') ? '1' : '0');
    } catch {
        // ignore
    }
}

document.addEventListener('click', onGridToggleClick);

document.addEventListener('DOMContentLoaded', applyGridFromStorage);

document.addEventListener('livewire:init', () => {
    if (typeof window.Livewire === 'undefined' || typeof window.Livewire.hook !== 'function') {
        return;
    }
    window.Livewire.hook('morph.updated', () => {
        requestAnimationFrame(applyGridFromStorage);
    });
});
