/**
 * THLD Bookstore — Toast Component
 * Migrated from public/js/components/toast.js
 */
export function initToast() {
    const toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    toastContainer.className = 'fixed bottom-8 right-8 z-[9999] flex flex-col gap-3 pointer-events-none';
    document.body.appendChild(toastContainer);

    window.showToast = function (message, type = 'info', title = '') {
        const icons = { success: 'check_circle', info: 'info', warning: 'warning', error: 'error' };
        const toast = document.createElement('div');
        toast.className = `thld-toast thld-toast-${type}`;
        toast.innerHTML = `
            <div class="thld-toast-icon flex items-center justify-center w-8 h-8 rounded-xl flex-shrink-0">
                <span class="material-symbols-outlined text-[20px]">${icons[type] ?? 'info'}</span>
            </div>
            <div class="flex-1">
                ${title ? `<div class="text-[13px] font-black text-slate-800 mb-0.5">${title}</div>` : ''}
                <div class="text-xs text-slate-500 font-medium">${message}</div>
            </div>`;
        toastContainer.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 50);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 600);
        }, 4000);
    };
}
