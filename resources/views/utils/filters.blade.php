<!--FILTERS-->
<form class="d-inline" id="filtersForm" method="post"
      action="@switch(true)
      @case (request()->routeIs('users.books'))
      {{ route('users.books', ['user' => $user]) }}
      @break
      @case (request()->routeIs('genres.books'))
      {{ route('genres.books', ['genres' => $genre->id]) }}
      @break
      @case (request()->routeIs('authors.books'))
      {{ route('authors.books', ['authors' => $author->id]) }}
      @break
      @default
      {{ route('books.index') }}
      @endswitch
      ">
    @csrf
    <div class="btn-group mt-2" role="group" aria-label="Filters">

        @if (isset($params['genres']))
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-dark dropdown-toggle"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    Жанр
                </button>
                <ul class="dropdown-menu p-0 overflow-scroll" style="height: 300%;">
                    @foreach($params['genres'] as $genre)
                        <li class="me-2">
                            <input type="checkbox" class="btn-check dropdown-item" autocomplete="off"
                                   id="btn-check-genre-{{ $genre->id }}" name="genres-{{ $genre->id }}"
                                    @checked(isset($filters['genres']) && in_array($genre->id, $filters['genres']))>
                            <label class="btn form-control text-start"
                                   for="btn-check-genre-{{ $genre->id }}">{{ $genre->name }}</label>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(isset($params['authors']))
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-dark dropdown-toggle"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    Автор
                </button>
                <ul class="dropdown-menu p-0 overflow-scroll" style="height: 300%;">
                    @foreach($params['authors'] as $author)
                        <li class="me-2">
                            <input type="checkbox" class="btn-check dropdown-item" autocomplete="off"
                                   id="btn-check-author-{{ $author->id }}" name="authors-{{ $author->id }}"
                                    @checked(isset($filters['authors']) && in_array($author->id, $filters['authors']))>
                            <label class="btn form-control text-start"
                                   for="btn-check-author-{{ $author->id }}">{{ $author->formattedName }}</label>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(isset($params['years']))
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-dark dropdown-toggle"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    Год издания
                </button>
                <ul class="dropdown-menu p-0 overflow-scroll" style="height: 300%;">
                    @foreach($params['years'] as $year)
                        <li class="me-2">
                            <input type="checkbox" class="btn-check dropdown-item" autocomplete="off"
                                   id="btn-check-year-{{ $year }}" name="years-{{ $year }}"
                                    @checked(isset($filters['years']) && in_array($year, $filters['years']))>
                            <label class="btn form-control text-start"
                                   for="btn-check-year-{{ $year }}">{{ $year }}</label>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(isset($params['book_types']))
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-dark dropdown-toggle"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    Тип
                </button>
                <ul class="pe-2 dropdown-menu p-0 overflow-scroll" style="height: 300%; min-width: 165px;">
                    @foreach($params['book_types'] as $type)
                        <li>
                            <input type="checkbox" class="btn-check dropdown-item" autocomplete="off"
                                   id="btn-check-type-{{ $type->id }}" name="book_types-{{ $type->id }}"
                                    @checked(isset($filters['book_types']) && in_array($type->id, $filters['book_types']))>
                            <label class="btn form-control text-start"
                                   for="btn-check-type-{{ $type->id }}">{{ $type->name }}</label>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="btn-group mt-2 ms-2">
        <button type="submit" id="filterBooksBtn" class="btn btn-outline-dark">
            Применить
        </button>
    </div>

    <div class="btn-group mt-2 ms-2">
        <button type="button" id="dropFiltersBtn" class="btn btn-outline-dark">
            Сбросить
        </button>
    </div>
</form>