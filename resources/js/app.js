import './bootstrap';
import './seating-chart-editor';
import './admin-floor-plan';
import { initAdminSidebar } from './admin-sidebar';
import { initToasts, registerLivewireNotify, showToast } from './toasts';

initToasts();
registerLivewireNotify();
window.showToast = showToast;

document.addEventListener('DOMContentLoaded', () => {
    initAdminSidebar();
});
