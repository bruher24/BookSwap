export async function showFadeAlert(toCopy, message) {
    await navigator.clipboard.writeText(toCopy);
    const toastEl = $(
        `<div class="toast align-items-center text-white bg-success border-0" 
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle me-2"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`
    );

    const toastContainer = $('<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3" style="z-index: 1100;"></div>')
        .append(toastEl)
        .appendTo('body');

    const toast = new bootstrap.Toast(toastEl[0]);

    toast.show();

    setTimeout(() => {
        toast.hide();
        toastEl.on('hidden.bs.toast', function () {
            toastContainer.remove();
        });
    }, 3000);
}

export function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    $('#ajaxAlerts').append(alertHtml);

    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
}
