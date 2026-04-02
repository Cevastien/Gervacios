/**
 * Admin layout: collapsible sidebar (icon rail), persisted in localStorage.
 */
const STORAGE_KEY = 'admin_sidebar_collapsed';

export function initAdminSidebar() {
    const toggle = document.getElementById('admin-sidebar-toggle');
    if (!toggle) {
        return;
    }

    const root = document.documentElement;

    function syncAria() {
        const collapsed = root.classList.contains('admin-sidebar-collapsed');
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        toggle.setAttribute(
            'title',
            collapsed ? 'Expand navigation' : 'Collapse navigation',
        );
    }

    toggle.addEventListener('click', () => {
        root.classList.toggle('admin-sidebar-collapsed');
        try {
            localStorage.setItem(
                STORAGE_KEY,
                root.classList.contains('admin-sidebar-collapsed') ? '1' : '0',
            );
        } catch {
            // ignore
        }
        syncAria();
    });

    syncAria();
}
