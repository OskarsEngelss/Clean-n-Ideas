/**
 * Atbild par kļūdu paziņojumu (toast) automātisku aizvēršanu un noņemšanu.
 * * Pēc noteikta laika (3 sekundēm) paslēpj servera atgriezto kļūdu paziņojumu, 
 * lai uzlabotu lietotāja saskarnes pārskatāmību.
 */

export function initPageRefreshErrorToast() {
    const toast = document.getElementById('server-error-toast');
    if (toast) {
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
}