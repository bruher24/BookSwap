import * as utils from "./utils.js";

$('.authorize-btn').on('click', e => {
    e.preventDefault();
    localStorage.removeItem('api_token');

    let email = $('#floatingEmail').val();
    let password = $('#floatingPassword').val();
    getApiToken({
        email: email,
        password: password
    }).then(response => {
        console.log(response)
        localStorage.setItem('api_token', response.data.token);
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;
        $(e.target).parent().submit();
    }).catch(e => {
        utils.showAlert('Ошибка получения токена');
    });
});

$('#logout-btn').on('click', e => {
    e.preventDefault();
    localStorage.removeItem('api-token');
    window.location.replace('http://localhost:3000/users/logout');
});

async function getApiToken(credentials) {
    console.log(credentials);
    credentials.email = 'admin@admin.com';
    return await window.axios.post('/api/login', {
        email: credentials.email,
        password: credentials.password,
    });

}
