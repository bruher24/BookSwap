$(async function () {
    if (window.Laravel.user && !localStorage.getItem('api_token')) {
        let email = $('#floatingEmail').val();
        let password = $('#floatingPassword').val();
        getApiToken({
            email: email,
            password: password
        }).then(response => {
            if (response.data.success) {
                localStorage.setItem('api_token', response.data.token);
                window.axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
            }
        });
    }
});

async function getApiToken(credentials) {
    credentials.email = 'admin@admin.com';
    return await window.axios.post('/api/login', {
        email: credentials.email,
        password: credentials.password,
    }, {
        headers: {
            'Accept': 'application/json',
        },
    });

}
