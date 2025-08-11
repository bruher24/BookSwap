import './echo.js';
import * as utils from "./utils.js";

window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('api_token')}`;

let userId = $('.chat-container').data('user');
let recipientId;
let $messagesContainer = $('.messages-container');
let unreadMessages = [];
let readMessages = [];
let counter = 0;


userId = userId ?? (window.Laravel.user ? window.Laravel.user.id : null);
if (userId) {
    unreadMessagesRequest(userId)
        .then(response => {
            if (response.data.messages.length > 0) {
                for (let message of response.data.messages) {
                    unreadMessages.push(message.id);
                    let obj = $(`.chat-row[data-recipient="${message.from_id}"]`);
                    if (obj.length > 0) {
                        obj.children('.new-message-icon').show();
                    }
                }
            }

            let queryRecipient = $('#query-recipient').val();
            if (queryRecipient !== '') {
                let obj = $(`.chat-row[data-recipient="${queryRecipient}"]`);
                if (obj.length > 0) {
                    recipientId = obj.data('recipient');
                    selectChat(obj);
                }
            }
        })
        .catch(e => {
            if (e.response.data.message) {
                utils.showAlert('danger', e.response.data.message);
            }
        });

    Echo.private(`user.${userId}`)
        .listen('MessageSent', (socketMessage) => {
            let obj = $(`.chat-row[data-recipient="${socketMessage.message.from_id}"]`);
            if (obj.length > 0) {
                if (obj.hasClass('active')) {
                    appendMessage(socketMessage.message);
                    // TODO: текст внизу "непрочитанных сообщений"
                }

                unreadMessages.push(socketMessage.message.id);

                obj.children('.new-message-icon').show();
                $($messagesContainer).trigger('scroll');
            }
            // TODO: показать уведомление
            //  отправлять два сообщения: в канал уведомлений и в канал чата ??
        });
}

$('.chat-row ').on('click', function () {
    recipientId = $(this).data('recipient');
    selectChat($(this));
});

$($messagesContainer).on('scroll', () => {
    unreadMessages.forEach((unreadMessageId, key) => {
        let $unreadMessageDiv = $(`div[id="message-${unreadMessageId}"]`);
        if ($unreadMessageDiv.length > 0 && $unreadMessageDiv.isInDiv()) {
            $('.chat-row.active').children('.new-message-icon').hide();
            readMessages.push(unreadMessageId);
            delete (unreadMessages[key]);
        }
    });

    if (readMessages.length > 0) {
        markAsReadRequest(readMessages)
            .then(response => {
                counter = 0;
            })
            .catch(e => {
                if (e.response.data.message) {
                    utils.showAlert('danger', e.response.data.message);
                }
            });
    }
});

// TODO: добавить проверку даты, чтобы динамически дорисовывать ее
$('#sendMessageBtn').on('click', () => {
    let textInput = $('#messageInput');
    let text = textInput.val();
    textInput.val('');
    sendMessageRequest(text)
        .then(response => {
            if ($messagesContainer.html() === '') {
                let dateObj = new Date(response.data.message.created_at);
                let options = {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                };
                let formattedDate = dateObj.toLocaleDateString('ru-RU', options);

                appendDateSection(formattedDate);
            }

            appendMessage(response.data.message);

            $messagesContainer.animate({
                scrollTop: $messagesContainer.prop('scrollHeight')
            }, 550);
        })
        .catch(e => {
            if (e.response.data.message) {
                utils.showAlert('danger', e.response.data.message);
            }
        });
});


function selectChat($node) {
    if (!$node.hasClass('active')) {
        madeActive($node);

        messagesListRequest(userId)
            .then(response => {
                displayChat(response.data);

                $($messagesContainer).trigger('scroll');
            })
            .catch(e => {
                if (e.response.data.message) {
                    utils.showAlert('danger', e.response.data.message);
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
    return await window.axios.get(`/api/v1/users/${userId}/chat/${recipientId}`);
}

function displayChat(data) {

    displayTypingArea(data.is_blocked);

    displayChatHeader(data.recipient);

    displayMessages(data.messages, data.recipient);
}

function displayTypingArea(is_blocked) {

    if (is_blocked) {
        $('#chat-blocked-text').show();
    } else {
        $('.no-messages-text').hide();
        $('.typing-area').show();
    }
}

function displayChatHeader(recipient) {
    $('.chat-header p').text('Чат с ' + recipient.name);
}

function displayMessages(messages) {
    $messagesContainer.empty();

    for (const [date, content] of Object.entries(messages)) {
        let dateObj = new Date(date);
        let options = {
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        let formattedDate = dateObj.toLocaleDateString('ru-RU', options);

        appendDateSection(formattedDate);
        content.forEach((message) => {
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

    let $messageDiv = $('<div>').attr('id', 'message-' + message.id).addClass('message-div rounded-3 px-2 py-1 mx-2 my-1').css('max-width', '45%');
    let $messageBody = $('<p>').addClass('message-body p-0 m-0 pe-4').text(message.body);
    let $messageTime = $('<p>').addClass('message-date p-0 m-0 small text-secondary text-end').text(time);

    let divClasses = ['bg-none', 'border', 'border-black', 'text-start', 'me-auto'];

    if (message.from_id === userId) {
        divClasses = ['bg-dark-subtle', 'text-end', 'ms-auto'];
    }

    divClasses.forEach((className) => {
        $messageDiv.addClass(className);
    });

    $messageDiv.append($messageBody);
    $messageDiv.append($messageTime);

    $messagesContainer.append($messageDiv);
    counter++;
}

async function sendMessageRequest(text) {
    return await window.axios.post(`/api/v1/users/${userId}/chat/${recipientId}/message`, {
        body: text
    });
}

$.fn.isInDiv = function () {
    if (this.length === 0) return false;

    const element = $(this)[0];
    const containerEl = $(this).parent()[0];

    const containerRect = containerEl.getBoundingClientRect();
    const elementRect = element.getBoundingClientRect();

    return (
        elementRect.bottom > containerRect.top &&
        elementRect.top < containerRect.bottom &&
        elementRect.right > containerRect.left &&
        elementRect.left < containerRect.right
    );
};

async function unreadMessagesRequest(userId) {
    return await window.axios.get(`/api/v1/users/${userId}/messages`);
}

async function markAsReadRequest(messages) {
    return await window.axios.patch(`/api/v1/users/${userId}/messages/read`, {
        messages: messages
    });
}
