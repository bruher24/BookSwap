<div id="check-all-div" class="d-flex" role="{{ $notifications->isNotEmpty() ? 'button' : 'none' }}"
     style="width: fit-content">
    <a class="me-1 small text-black" style="text-decoration: none;">Отметить все как прочитанные</a>
    <svg xmlns="http://www.w3.org/2000/svg"
         width="21"
         height="21"
         fill="black"
         class="bi bi-check2-all"
         viewBox="0 0 16 16">
        <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0"/>
        <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708"/>
    </svg>
</div>

<div id="notifications-container" class="mt-3" data-user="{{ $user->id }}" style="width: 40%;">
    @foreach($notifications as $notification)
        <div class="notification-div shadow-sm rounded mt-2 p-3 d-flex flex-column"
             role="button"
             data-notification="{{ $notification->id }}">
            <a class="text-black" style="text-decoration: none;">
                {{--$notification->icon--}} {{ $notification->subject }}
            </a>

            <a class="d-flex justify-content-between text-black ms-2" style="text-decoration: none;">
                {{ $notification->body }}
                <small>{{ $notification->updated_at->format('H:i d.m.Y') }}</small>
            </a>

            <div class="hidden-div bg-secondary-subtle" style="display: none;">
                123
            </div>
        </div>
    @endforeach
</div>
