import './echo.js';

let userId = $('.chat-container').data('user');
let recipientId;
let csrf_token = $('input[name="_token"]').val();
let $messagesContainer = $('.messages-container');

$(function () {
    let queryRecipient = $('#query-recipient').val();
    if (queryRecipient !== '') {
        let obj = $(`.chat-row[data-recipient="${queryRecipient}"]`);
        if (obj.length > 0) {
            recipientId = obj.data('recipient');
            selectChat(obj);
        }
    }

    $('.chat-row ').on('click', function () {
        recipientId = $(this).data('recipient');
        selectChat($(this));
    });

    Echo.private(`user.${userId}`)
        .listen('MessageSent', (e) => {

            // TODO: если юзер в списке чатов, показать плюсик в строке чата
            //  иначе отрисовать сообщение в чате
            appendMessage(e.message);

            $messagesContainer.animate({
                scrollTop: $messagesContainer.prop('scrollHeight')
            }, 550);

            // TODO: показать уведомление
            //  отправлять два сообщения: в канал уведомлений и в канал чата ??
        });

    $('#sendMessageBtn').on('click', function () {
        let textInput = $('#messageInput');
        let text = textInput.val();
        textInput.val('');
        sendMessage(text).then(function (response) {
            console.log(response);
            if (response.success) {
                appendMessage(response.message);

                $messagesContainer.animate({
                    scrollTop: $messagesContainer.prop('scrollHeight')
                }, 550);
            }
        });
    });
});

function selectChat($node) {
    if (!$node.hasClass('active')) {
        madeActive($node);

        messagesListRequest(userId)
            .then(function (response) {
                if (response.success) {
                    displayChat(response);

                    $messagesContainer.animate({
                        scrollTop: 9999999
                    }, 550);
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

async function messagesListRequest(userId) {
    return await $.ajax({
        url: `/api/v1/users/${userId}/chat/${recipientId}`,
        type: 'get',
        async: true
    });
}

function displayChat(response) {

    displayTypingArea();

    displayChatHeader(response.recipient);

    displayMessages(response.messages, response.recipient);
}

function displayTypingArea() {
    $('.no-messages-text').toggleClass('d-none');
    $('.typing-area').toggleClass('d-none');
}

function displayChatHeader(recipient) {
    $('.chat-header p').text('Чат с ' + recipient.name);
}

function displayMessages(messages) {
    for (const [date, content] of Object.entries(messages)) {
        let dateObj = new Date(date);
        let options = {
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        let formattedDate = dateObj.toLocaleDateString('ru-RU', options);

        appendDateSection(formattedDate);
        content.forEach(function (message) {
            appendMessage(message);
        });
    }
}

function appendDateSection(date) {
    $messagesContainer.append(`<p class="p-0 pb-3 m-auto text-secondary">${date}</p>`);
}

function appendMessage(message) {
    let created_at = new Date(message.created_at);
    let time = created_at.getHours() + ':' + (created_at.getMinutes() < 10 ? '0' + created_at.getMinutes() : created_at.getMinutes());

    let $messageDiv = $('<div>').addClass('message-div rounded-3 px-2 py-1 mx-2 my-1').css('max-width', '45%');
    let $messageBody = $('<p>').addClass('message-body p-0 m-0 pe-4').text(message.body);
    let $messageTime = $('<p>').addClass('message-date p-0 m-0 small text-secondary text-end').text(time);

    let divClasses = ['bg-none', 'border', 'border-black', 'text-start', 'me-auto'];

    if (message.from_id === userId) {
        divClasses = ['bg-dark-subtle', 'text-end', 'ms-auto'];
    }

    divClasses.forEach(function (className) {
        $messageDiv.addClass(className);
    });

    $messageDiv.append($messageBody);
    $messageDiv.append($messageTime);

    $messagesContainer.append($messageDiv);
}

async function sendMessage(text) {
    return await $.ajax({
        url: `/api/v1/users/${userId}/chat/${recipientId}/message`,
        type: 'post',
        async: true,
        data: {
            body: text,
            _token: csrf_token
        }
    });
}
