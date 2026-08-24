<?php

namespace App\Services;

use App\Interfaces\PhotoServiceInterface;
use App\Jobs\DeleteFileJob;
use App\Models\Photo;
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

final class PhotoService implements PhotoServiceInterface
{
    #[Override]
    public function create(array $data): Photo|false
    {
        try {
            return DB::transaction(function () use ($data) {
                $path = $this->storeFile($data['src']);

                if ($path === false) {
                    throw new Exception('Ошибка при сохранении файла');
                }

                $photo = Photo::create(['src' => $path, 'user_id' => $data['user_id']]);
                return $photo->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(int $id): Photo|false
    {
        try {
            return Photo::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(Photo::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Photo::CACHE_KEY);
                return Photo::all();
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
            return Photo::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function delete(Photo $photo): bool
    {
        try {
            return DB::transaction(function () use ($photo) {
                $oldPath = $photo->src;
                $deleted = $photo->deleteOrFail();

                if (isset($oldPath) && $photo->id !== Photo::BASE_PHOTO_ID) {
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
        return Storage::disk('public')->putFileAs('avatars', $file, $uuid . "." . $fileType);
    }
}
