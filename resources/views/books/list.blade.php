<div class="row mt-3">
    @foreach($books as $book)
        <div class="book-card col-auto rounded-1 border shadow mb-5 me-2 p-0 d-flex flex-column"
             style="width: 200px; height: 320px; cursor: pointer;"
             data-book="{{ $book->id }}">
            <img alt="Упс! Произошла ошибка." src="MAIN_IMG_LINK" onerror="this.onerror=null; this.src='SPARE_IMG_LINK'"
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
        </div>
    @endforeach
</div>