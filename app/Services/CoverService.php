<?php

namespace App\Services;

use App\Interfaces\CoverServiceInterface;
use App\Jobs\DeleteFileJob;
use App\Models\Cover;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Override;
use Throwable;

final class CoverService implements CoverServiceInterface
{
    #[Override]
    public function create(array $data): Cover|false
    {
        try {
            return DB::transaction(function () use ($data) {
                $path = $this->storeFile($data['file']);

                if ($path === false) {
                    throw new Exception('Ошибка при сохранении файла');
                }

                $cover = Cover::create(['src' => $path, 'user_id' => $data['user_id']]);
                return $cover->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(int $id): Cover|false
    {
        try {
            return Cover::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(Cover::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Cover::CACHE_KEY);
                return Cover::all();
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
            return Cover::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function delete(Cover $cover): bool
    {
        try {
            return DB::transaction(function () use ($cover) {
                $oldPath = $cover->src;
                $deleted = $cover->deleteOrFail();

                if (isset($oldPath) && $cover->id !== Cover::BASE_COVER_ID) {
                    DeleteFileJob::dispatch($oldPath)->afterCommit();
                }

                return $deleted;
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    private function storeFile(UploadedFile $file): false|string
    {
        $uuid = Str::uuid()->toString();
        $fileType = $file->getClientOriginalExtension();
        return Storage::disk('public')->putFileAs('covers', $file, $uuid . "." . $fileType);
    }
}
