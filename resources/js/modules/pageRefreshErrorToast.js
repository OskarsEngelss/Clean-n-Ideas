export function initPageRefreshErrorToast() {
    const toast = document.getElementById('server-error-toast');
    if (toast) {
        console.log('heelo');
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
}