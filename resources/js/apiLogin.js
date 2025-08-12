import * as utils from "./utils.js";

$('.authorize-btn').on('click', function (e) {
    e.preventDefault();
    localStorage.removeItem('api_token');

    let email = $('#floatingEmailLogin').val();
    let password = $('#floatingPasswordLogin').val();

    getApiToken({
        email: email,
        password: password
    }).then(response => {
        localStorage.setItem('api_token', response.data.token);
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;
        $(this).parent().submit();
    }).catch(e => {
        if (e.response.data.message) {
            utils.showAlert('danger', e.response.data.message);
        }
    });
});

$('#logout-btn').on('click', e => {
    e.preventDefault();
    localStorage.removeItem('api-token');
    window.location.replace('http://localhost/users/logout');
});

async function getApiToken(credentials) {
    return await window.axios.post('/api/login', {
        email: credentials.email,
        password: credentials.password,
    });

}
