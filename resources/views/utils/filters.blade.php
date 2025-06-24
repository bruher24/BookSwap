<!--FILTERS-->
<form>
    <div class="btn-group mt-2" role="group" aria-label="Filters">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-dark dropdown-toggle"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                Жанр
            </button>
            <ul class="dropdown-menu p-0">
                @foreach($genres as $genre)
                    <li>
                        <input type="checkbox" class="btn-check dropdown-item" id="btn-check-genre-{{ $genre->id }}" autocomplete="off">
                        <label class="btn form-control text-start" for="btn-check-genre-{{ $genre->id }}">{{ $genre->name }}</label>
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
                @foreach($authors as $author)
                    <li>
                        <input type="checkbox" class="btn-check dropdown-item" id="btn-check-author-{{ $author->id }}" autocomplete="off">
                        <label class="btn form-control text-start" for="btn-check-author-{{ $author->id }}">{{ $author->formattedName }}</label>
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
                @foreach($years as $year)
                    <li>
                        <input type="checkbox" class="btn-check dropdown-item" id="btn-check-year-{{ $year }}" autocomplete="off">
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
                @foreach($types as $type)
                    <li>
                        <input type="checkbox" class="btn-check dropdown-item" id="btn-check-type-{{ $type->id }}" autocomplete="off">
                        <label class="btn form-control text-start" for="btn-check-type-{{ $type->id }}">{{ $type->name }}</label>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</form>