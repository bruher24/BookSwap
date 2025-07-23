<div class="chats-container row mt-3 border border-black w-100 p-0 m-0" style="height: 83vh">
    <div class="chat-list col-sm-3 p-0 m-0 border-end border-black">
        @php
            $chats = [1];
        @endphp

        @if(!empty($chats))
            {{--            @foreach($chats as $chat)--}}
            {{--                <div class="chat-row border-bottom border-black d-flex align-items-center" style="min-height: 8%">--}}
            {{--                    <img class="m-1"--}}
            {{--                         src="{{ Storage::disk('public')->url($chat->user->photo->src) }}"--}}
            {{--                         alt="Упс! Что-то пошло не так."--}}
            {{--                         style="max-height:23%; max-width:23%; height:auto; width:auto;">--}}
            {{--                    <p class="p-0 m-0 ms-2">{{ $chat->user->name }}</p>--}}
            {{--                </div>--}}
            {{--            @endforeach--}}

            <!--TEST-->
            <div class="chat-row border-bottom border-black d-flex align-items-center" role="button"
                 style="min-height: 8%">
                <img class="m-1"
                     src="{{ Storage::disk('public')->url($user->photo->src) }}"
                     alt="Упс! Что-то пошло не так."
                     style="max-height:23%; max-width:23%; height:auto; width:auto;">
                <p class="p-0 m-0 ms-2">{{ $user->name }}</p>
            </div>

            <div class="chat-row border-bottom border-black d-flex align-items-center" role="button"
                 style="min-height: 8%">
                <img class="m-1"
                     src="{{ Storage::disk('public')->url($user->photo->src) }}"
                     alt="Упс! Что-то пошло не так."
                     style="max-height:23%; max-width:23%; height:auto; width:auto;">
                <p class="p-0 m-0 ms-2">{{ $user->name }}</p>
            </div>
            <!--ENDTEST-->

        @else
            <div class="d-flex align-items-center h-100">
                <p class="p-0 m-auto text-center text-secondary">Кажется, тут пусто...</p>
            </div>
        @endif
    </div>

    <div class="chat-window col p-0 m-0 d-flex align-items-center">
        <p class="text-center text-secondary m-auto">Выберите чат, чтобы начать общение</p>
    </div>
</div>