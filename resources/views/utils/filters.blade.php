<!--FILTERS-->
<form id="filtersForm" method="post"
      action="
      @if (request()->routeIs('users.books'))
      {{ route('users.books', ['user' => $user]) }}
      @else
      {{ route('books.index') }}
      @endif
      ">
    @csrf
    <div class="btn-group mt-2" role="group" aria-label="Filters">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-dark dropdown-toggle"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                Жанр
            </button>
            <ul class="dropdown-menu p-0">
                @foreach($params['genres'] as $genre)
                    <li>
                        <input type="checkbox" class="btn-check dropdown-item" autocomplete="off"
                               id="btn-check-genre-{{ $genre->id }}" name="genre-{{ $genre->id }}"
                                @checked(isset($filters['genre']) && in_array($genre->id, $filters['genre']))>
                        <label class="btn form-control text-start"
                               for="btn-check-genre-{{ $genre->id }}">{{ $genre->name }}</label>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-dark dropdown-toggle"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                Автор
            </button>
            <ul class="dropdown-menu p-0">
                @foreach($params['authors'] as $author)
                    <li>
                        <input type="checkbox" class="btn-check dropdown-item" autocomplete="off"
                               id="btn-check-author-{{ $author->id }}" name="author-{{ $author->id }}"
                                @checked(isset($filters['author']) && in_array($author->id, $filters['author']))>
                        <label class="btn form-control text-start"
                               for="btn-check-author-{{ $author->id }}">{{ $author->formattedName }}</label>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-dark dropdown-toggle"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                Год издания
            </button>
            <ul class="dropdown-menu p-0">
                @foreach($params['years'] as $year)
                    <li>
                        <input type="checkbox" class="btn-check dropdown-item" autocomplete="off"
                               id="btn-check-year-{{ $year }}" name="year-{{ $year }}"
                                @checked(isset($filters['year']) && in_array($year, $filters['year']))>
                        <label class="btn form-control text-start" for="btn-check-year-{{ $year }}">{{ $year }}</label>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-dark dropdown-toggle"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                Тип
            </button>
            <ul class="dropdown-menu p-0">
                @foreach($params['types'] as $type)
                    <li>
                        <input type="checkbox" class="btn-check dropdown-item" autocomplete="off"
                               id="btn-check-type-{{ $type->id }}" name="type-{{ $type->id }}"
                                @checked(isset($filters['type']) && in_array($type->id, $filters['type']))>
                        <label class="btn form-control text-start"
                               for="btn-check-type-{{ $type->id }}">{{ $type->name }}</label>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="btn-group mt-2 ms-2">
        <button type="submit" id="filterBooksBtn" class="btn btn-outline-dark">
            Применить
        </button>
    </div>
</form>