<div class="chat-list-container col-sm-3 p-0 m-0 border-end border-black h-100">
    @if(!empty($chats))
        <div class="border-bottom border-black" style="height: 8%"></div>
        <div class="chat-list h-100">
            @foreach($chats as $chat)
                <div class="chat-row border-bottom border-black d-flex align-items-center justify-content-between"
                     role="button"
                     data-recipient="{{ $chat->other_user($user)->id }}"
                     style="height: 8%">
                    <div class="d-flex align-items-center h-100">
                        <img class="m-1"
                             src="{{ Storage::disk('public')->url($chat->other_user($user)->photo->src) }}"
                             alt="Упс! Что-то пошло не так."
                             style="max-height:70%; max-width:70%; height:auto; width:auto;">
                        <p class="p-0 m-0 ms-2">{{ $chat->other_user($user)->name }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="black"
                         class="bi bi-circle-fill me-3 new-message-icon" viewBox="0 0 16 16" style="display: none">
                        <circle cx="8" cy="8" r="8"/>
                    </svg>
                </div>
            @endforeach
            @else
                <div class="d-flex align-items-center h-100">
                    <p class="p-0 m-auto text-center text-secondary">Кажется, тут пусто...</p>
                </div>
            @endif
        </div>
</div>
