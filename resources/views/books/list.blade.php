@php use Illuminate\Support\Facades\Storage; @endphp
<div class="row mt-3">
    @if ($books->isNotEmpty())
        @foreach($books as $book)
            <div class="book-card col-auto rounded-1 border shadow mb-5 me-2 p-0 d-flex flex-column"
                 style="width: 200px; height: 320px;"
                 data-book="{{ $book->id }}">
                <a href="{{ route('books.show', ['book' => $book->id]) }}" class="nav-link">
                    <img alt="Упс! Произошла ошибка."
                         src="{{ Storage::disk('local')->url($book->cover->src) }}"
                         class="img-fluid rounded-top"
                         style="width: 100%; height: 250px; object-fit: cover; text-align: center; line-height: 250px; color: grey">
                    <div class="p-2 flex-grow-1 d-flex flex-column">
                        <h6 class="mb-1 ms-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
                        overflow: hidden; text-overflow: ellipsis; min-height: 2.5em; line-height: 1.25em;">
                            {{ $book->name }}
                            <br>
                            <small>{{ $book->mainAuthor }}</small>
                        </h6>
                    </div>
                </a>
            </div>
        @endforeach
    @else
        <h4 class="mt-4 text-secondary">Упс! Ничего не найдено...</h4>
    @endif
</div>