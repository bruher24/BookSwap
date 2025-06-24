<!--FILTERS-->
<div class="btn-group mt-2" role="group" aria-label="Filters">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Жанр
        </button>
        <ul class="dropdown-menu">
            @foreach($genres as $genre)
                <li><a class="dropdown-item" href="#">{{ $genre->name }}</a></li>
            @endforeach
        </ul>
    </div>

    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Автор
        </button>
        <ul class="dropdown-menu">
            @foreach($authors as $author)
                <li><a class="dropdown-item" href="#">{{ $author->formattedName }}</a></li>
            @endforeach
        </ul>
    </div>

    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Год издания
        </button>
        <ul class="dropdown-menu">
            @foreach($years as $year)
                <li><a class="dropdown-item" href="#">{{ $year }}</a></li>
            @endforeach
        </ul>
    </div>

    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Тип
        </button>
        <ul class="dropdown-menu">
            @foreach($types as $type)
                <li><a class="dropdown-item" href="#">{{ $type->name }}</a></li>
            @endforeach
        </ul>
    </div>
</div>