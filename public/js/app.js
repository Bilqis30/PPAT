/**
 * WebArsipp - Core Application JavaScript
 * Handles global interactions, mobile navigation, and inline PDF preview modal.
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Lucide Icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // 2. Global Escape Key Listener for Modals & Mobile Sidebar
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closePdfModal();
            closeDeleteModal();
            closeSidebar();
            closeNotificationDropdown();
        }
    });

    // 2.1 Close notification dropdown when clicking outside
    document.addEventListener('click', (e) => {
        const container = document.getElementById('notification-dropdown-container');
        const menu = document.getElementById('notification-dropdown-menu');
        if (container && menu && !container.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // 3. Auto dismiss success alerts after 6 seconds
    const autoDismissAlerts = document.querySelectorAll('.auto-dismiss-alert');
    if (autoDismissAlerts.length > 0) {
        setTimeout(() => {
            autoDismissAlerts.forEach((alert) => {
                alert.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 6000);
    }
});

/**
 * Mobile Sidebar Drawer Controllers
 */
function toggleSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (!sidebar || !backdrop) return;

    const isClosed = sidebar.classList.contains('-translate-x-full');
    if (isClosed) {
        openSidebar();
    } else {
        closeSidebar();
    }
}

function openSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (!sidebar || !backdrop) return;

    sidebar.classList.remove('-translate-x-full');
    backdrop.classList.remove('hidden');
    document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
}

function closeSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (!sidebar || !backdrop) return;

    sidebar.classList.add('-translate-x-full');
    backdrop.classList.add('hidden');
    document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
}

/**
 * Global Inline PDF Preview Modal
 */
function openPdfModal(url, title, downloadUrl) {
    const modal = document.getElementById('pdf-preview-modal');
    const iframe = document.getElementById('pdf-iframe');
    const titleEl = document.getElementById('pdf-modal-title');
    const downloadBtn = document.getElementById('pdf-modal-download-btn');
    const newTabBtn = document.getElementById('pdf-modal-newtab-btn');

    if (!modal || !iframe) return;

    if (titleEl) titleEl.textContent = title || 'Pratinjau Dokumen PDF';
    if (downloadBtn) downloadBtn.href = downloadUrl || url;
    if (newTabBtn) newTabBtn.href = url;

    iframe.src = url;
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function closePdfModal() {
    const modal = document.getElementById('pdf-preview-modal');
    const iframe = document.getElementById('pdf-iframe');
    if (!modal) return;

    modal.classList.add('hidden');
    if (iframe) iframe.src = '';
    document.body.classList.remove('overflow-hidden');
}

/**
 * Global Delete Confirmation Modal Controllers
 */
function openDeleteModal(actionUrl, documentTitle) {
    const modal = document.getElementById('delete-confirm-modal');
    const form = document.getElementById('delete-confirm-form');
    const titleEl = document.getElementById('delete-confirm-doc-title');

    if (!modal || !form) return;

    form.action = actionUrl;
    if (titleEl) {
        titleEl.textContent = documentTitle || 'dokumen ini';
    }

    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function closeDeleteModal() {
    const modal = document.getElementById('delete-confirm-modal');
    const form = document.getElementById('delete-confirm-form');

    if (!modal) return;

    modal.classList.add('hidden');
    if (form) {
        form.action = '';
    }
    document.body.classList.remove('overflow-hidden');
}

/**
 * Topbar Notification Dropdown Controllers
 */
function toggleNotificationDropdown() {
    const menu = document.getElementById('notification-dropdown-menu');
    if (!menu) return;

    menu.classList.toggle('hidden');
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function closeNotificationDropdown() {
    const menu = document.getElementById('notification-dropdown-menu');
    if (menu) {
        menu.classList.add('hidden');
    }
}


