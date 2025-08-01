<div class="chat-window col p-0 m-0 d-flex align-items-center flex-column h-100">

    <div class="chat-header border-bottom border-black w-100 d-flex" style="height: 8%">
        <p class="p-0 m-auto"></p>
    </div>

    <div class="messages-container w-100 m-auto d-flex flex-column overflow-scroll" style="height: 84%;">
        <p class="no-messages-text text-center text-secondary m-auto">Выберите чат, чтобы начать общение</p>
    </div>

    @csrf

    <div class="typing-area input-group border-top border-black d-none" style="height: 8%; width: 100%">
        <input id="messageInput" class="border-0 form-control" type="text" name="message"
               placeholder="Введите сообщение...">
        <svg id="sendMessageBtn" class="m-auto me-1" role="button" version="1.1" xmlns="http://www.w3.org/2000/svg"
             width="50"
             height="50"
             viewBox="0 0 32 32">
            <title>Отправить</title>
            <path d="M4.667 26.307v-7.983l17.143-2.304-17.143-2.304v-7.983l24 10.285z"></path>
        </svg>
    </div>
</div>
