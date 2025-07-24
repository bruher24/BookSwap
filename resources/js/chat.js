$(function () {
    $('.chat-row ').on('click', function () {
        selectChat($(this));
    });
});

function selectChat($node) {
    if (!$node.hasClass('active')) {
        madeActive($node);

        const user = $('.chats-container').data('user');
        let $chatWindow = $('.chat-window');

        let recipient = $node.data('recipient');

        messagesListRequest(user, recipient)
            .then(function (response) {
                if (response.success) {
                    displayChat($chatWindow, response.messages);
                }
            });
    }
}

function madeActive($node) {
    $('.chat-row').each(function () {
        $(this).removeClass('bg-dark-subtle active');
    });
    if (!$node.hasClass('bg-dark-subtle active')) {
        $node.toggleClass('bg-dark-subtle active');
    }
}

async function messagesListRequest(user, recipient) {
    return await $.ajax({
        url: `/api/v1/users/${user}/chat/${recipient}`,
        type: 'get',
        async: true
    });
}

function displayChat($chatWindow, messages) {
    let $messagesContainer = $('.messages-container');

    displayTypingArea();

    displayChatHeader($chatWindow);

    displayMessages($messagesContainer, messages);
}

function displayTypingArea() {
    $('.no-messages-text').toggleClass('d-none');
    $('.typing-area').toggleClass('d-none');
}

function displayChatHeader($chatWindow) {

}

function displayMessages($messagesContainer, messages) {
    console.log(messages);
}
