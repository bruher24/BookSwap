<div class="chat-list-container col-sm-3 p-0 m-0 border-end border-black h-100">
    @if(!empty($chats))
        <div class="border-bottom border-black" style="height: 8%"></div>
        <div class="chat-list h-100">
            @foreach($chats as $chat)
                <div class="chat-row border-bottom border-black d-flex align-items-center"
                     role="button"
                     data-recipient="{{ $chat->other_user($user)->id }}"
                     style="height: 8%">
                    <img class="m-1"
                         src="{{ Storage::disk('public')->url($chat->other_user($user)->photo->src) }}"
                         alt="Упс! Что-то пошло не так."
                         style="max-height:70%; max-width:70%; height:auto; width:auto;">
                    <p class="p-0 m-0 ms-2">{{ $chat->other_user($user)->name }}</p>
                </div>
            @endforeach
            @else
                <div class="d-flex align-items-center h-100">
                    <p class="p-0 m-auto text-center text-secondary">Кажется, тут пусто...</p>
                </div>
            @endif
        </div>
</div>
