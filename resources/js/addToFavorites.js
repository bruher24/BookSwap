$(function () {
    let $likeBtn = $('#like_btn');
    let user_id = $('#favorites_user_id').val();
    let book_id = $('#favorites_book_id').val();
    let state;

    $likeBtn.on('click', function () {
        if (user_id !== null) {
            $(this).toggleClass('liked');
            state = !!$('#favorites_state').val();
            addToFavoritesRequest(
                user_id,
                book_id,
                !state
            ).then(function (response) {
                if (response.success) {
                    location.reload();
                }
            });
        } else {
            const modalLogin = new bootstrap.Modal('#modalLogin')
            modalLogin.show();
        }
    });
});

async function addToFavoritesRequest(user_id, book_id, state) {
    let token = $('#add_to_favorites_form').children('input[name="_token"]').first().val();
    return await $.ajax({
        url: `/users/${user_id}/add-to-favorites`,
        type: 'put',
        async: true,
        data: {
            _token: token,
            book_id: book_id,
            state: state
        }
    });
}