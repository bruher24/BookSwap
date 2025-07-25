$(function () {
    $('.chat-row ').on('click', function () {
        selectChat($(this));
    });

    // TODO: слушать реальный канал
    Echo.private(`USER_CHANNEL`)
        .listen('MessageReceived', (e) => {
            console.log('Message received!', e);
            // TODO: показать уведомление + отобразить новое сообщение в чате
            //     отправлять два сообщения: в канал уведомлений и в канал чата ??
        });
});

function selectChat($node) {
    if (!$node.hasClass('active')) {
        madeActive($node);

        const user = $('.chat-container').data('user');
        let $chatWindow = $('.chat-window');

        let recipient = $node.data('recipient');

        messagesListRequest(user, recipient)
            .then(function (response) {
                if (response.success) {
                    displayChat($chatWindow, response);
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

function displayChat($chatWindow, response) {
    let $messagesContainer = $('.messages-container');

    displayTypingArea();

    displayChatHeader(response.recipient);

    displayMessages($messagesContainer, response.messages, response.recipient);
}

function displayTypingArea() {
    $('.no-messages-text').toggleClass('d-none');
    $('.typing-area').toggleClass('d-none');
}

function displayChatHeader(recipient) {
    $('.chat-header p').text('Чат с ' + recipient.name);
}

function displayMessages($messagesContainer, messages, recipient) {
    console.log(messages);
    for (const [date, content] of Object.entries(messages)) {
        let dateObj = new Date(date);
        let options = {
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        let formattedDate = dateObj.toLocaleDateString('ru-RU', options);

        appendDateSection($messagesContainer, formattedDate);
        content.forEach(function (message) {
            appendMessage($messagesContainer, message, recipient);
        });
    }
}

function appendDateSection($messagesContainer, date) {
    $messagesContainer.append(`<p class="p-0 pb-3 m-auto text-secondary">${date}</p>`);
}

function appendMessage($messagesContainer, message, recipient) {
    let created_at = new Date(message.created_at);
    let time = created_at.getHours() + ':' + created_at.getMinutes();

    let $messageDiv = $('<div>').addClass('message-div rounded-3 px-2 py-1 mx-2 my-1').css('max-width', '45%');
    let $messageBody = $('<p>').addClass('message-body p-0 m-0 pe-4').text(message.body);
    let $messageTime = $('<p>').addClass('message-date p-0 m-0 small text-secondary text-end').text(time);

    let divClasses = ['bg-none', 'border', 'border-black', 'text-start', 'me-auto'];

    if (message.to_id === recipient.id) {
        divClasses = ['bg-dark-subtle', 'text-end', 'ms-auto'];
    }

    divClasses.forEach(function (className) {
        $messageDiv.addClass(className);
    });

    $messageDiv.append($messageBody);
    $messageDiv.append($messageTime);

    $messagesContainer.append($messageDiv);
}
