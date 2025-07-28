<div class="chat-window col p-0 m-0 d-flex align-items-center flex-column h-100">

    <div class="chat-header border-bottom border-black w-100 d-flex" style="height: 8%">

        {{--        <svg height="32px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512"--}}
        {{--             width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"--}}
        {{--             xmlns:xlink="http://www.w3.org/1999/xlink">--}}
        {{--            <path d="M189.3,128.4L89,233.4c-6,5.8-9,13.7-9,22.4c0,8.7,3,16.5,9,22.4l100.3,105.4c11.9,12.5,31.3,12.5,43.2,0--}}
        {{--            c11.9-12.5,11.9-32.7,0-45.2L184.4,288h217c16.9,0,30.6-14.3,30.6-32c0-17.7-13.7-32-30.6-32h-217l48.2-50.4--}}
        {{--            c11.9-12.5,11.9-32.7,0-45.2C220.6,115.9,201.3,115.9,189.3,128.4z"/></svg>--}}

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