<?php

namespace App\Services;

use App\Interfaces\BookServiceInterface;
use App\Models\Book;
use App\Models\Cover;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class BookService extends Service implements BookServiceInterface
{
    public function __construct()
    {
        parent::__construct(Book::class);
        $this->ucFirstFields = [
            'name',
            'publishing_house',
        ];
    }

    public function create(array $data): Book|false
    {
        DB::beginTransaction();
        try {
            $data['cover_id'] = Cover::$baseCoverId;
            if (isset($data['cover'])) {
                $uuid = Str::uuid()->toString();
                $fileType = $data['cover']->getClientOriginalExtension();
                $path = Storage::disk('public')->putFileAs('covers', $data['cover'], $uuid . "." . $fileType);
                if ($path) {
                    $cover = new Cover([
                        'src' => $path,
                    ]);
                    $cover->save();
                    $data['cover_id'] = $cover->id;
                } else {
                    Log::error('Ошибка сохранения нового файла', [
                        'file' => $data['cover']
                    ]);
                }
            }

            $book = parent::create($data);
            if (!$book) {
                throw new Exception('Ошибка при создании книги');
            }

            $authors = $this->filterAuthorsData($data);
            if (!empty($authors) && !$this->attach($book, $authors)) {
                throw new Exception('Ошибка при добавлении авторов');
            }

            DB::commit();
            return $book;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function get(string $id): Book|false
    {
        return parent::get($id);
    }

    private function filterAuthorsData(array $data): array
    {
        $authors = [];

        for ($i = 0; $i < 4; $i++) {
            if (isset($data['author_id' . ($i == 0 ? '' : $i)])) {
                $authors['ids'][] = $data['author_id' . ($i == 0 ? '' : $i)];
            }
        }
        if (isset($data['authorFirstname'])) {
            $authors['new'] = [
                'lastname' => $data['authorLastname'],
                'firstname' => $data['authorFirstname'],
                'patronymic' => $data['authorPatronymic'],
                'birthdate' => $data['authorBirthdate'],
            ];
        }
        return $authors;
    }

    public function attach(string $book_id, array $authors): bool
    {
        try {
            $book = $this->get($book_id);
            if (isset($authors['ids'])) {
                $book->authors()->attach($authors['ids']);
            }

            if (isset($authors['new'])) {
                $book->authors()->create($authors['new']);
            }

            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
