$(function () {
    $('#call_btn').on('click', function () {
        if ($(this).data('user')) {
            let sellerPhone = $(this).data('seller');
            console.log(sellerPhone);
            let phoneDropdown = '<div><ul class="dropdown-menu position-static d-grid gap-1 p-2 rounded-3 mx-0 border-0 shadow w-220px" data-bs-theme="dark">\n' +
                '    <li><a class="dropdown-item rounded-2 active" href="#">Action</a></li>\n' +
                '    <li><a class="dropdown-item rounded-2" href="#">Another action</a></li>\n' +
                '    <li><a class="dropdown-item rounded-2" href="#">Something else here</a></li>\n' +
                '    <li>\n' +
                '        <hr class="dropdown-divider">\n' +
                '    </li>\n' +
                '    <li><a class="dropdown-item rounded-2" href="#">Separated link</a></li>\n' +
                '</ul></div>';
            $(this).parent().parent().append(phoneDropdown);
        } else {
            const modalLogin = new bootstrap.Modal('#modalLogin')
            modalLogin.show();
        }
    });
});