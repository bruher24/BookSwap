<?php

namespace App\Services;

use App\Interfaces\AuthorServiceInterface;
use App\Models\Author;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class AuthorService implements AuthorServiceInterface
{
    protected array $ucFirstFields = [
        'lastname',
        'firstname',
        'patronymic',
    ];

    private function formatData(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (in_array($key, $this->ucFirstFields)) {
                $value = ucfirst($value);
            }
        }
        return $data;
    }

    #[Override]
    public function create(array $data): Author|false
    {
        $formattedData = $this->formatData($data);

        try {
            DB::beginTransaction();
            $author = new Author($formattedData);

            if (!$author->save()) {
                throw new Exception("Ошибка при создании автора");
            }

            DB::commit();
            return $author->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(int $id): Author|false
    {
        try {
            return Author::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(Author::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Author::CACHE_KEY);
                return Author::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function where(string $field, string $value): Collection
    {
        try {
            return Author::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function update(Author $author, array $data): Author|false
    {
        try {
            DB::beginTransaction();
            $author->updateOrFail($data);
            DB::commit();
            return $author->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function delete(Author $author): bool
    {
        try {
            DB::beginTransaction();
            $author->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function byUser(User $user): Collection
    {
        return $user->authors()
            ->withoutTrashed()
            ->get();
    }
}
