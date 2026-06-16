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
use Throwable;

final class PhotoService implements PhotoServiceInterface
{
    public function create(array $data): Photo|false
    {
        try {
            DB::beginTransaction();
            $path = $this->storeFile($data['src']);

            if ($path === false) {
                throw new Exception('Ошибка при сохранении файла');
            }

            $photo = new Photo(['src' => $path, 'user_id' => $data['user_id']]);

            if (!$photo->save()) {
                throw new Exception("Ошибка при создании фото");
            }

            DB::commit();
            return $photo->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function get(string $id): Photo|false
    {
        try {
            return Photo::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

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

    public function delete(Photo $photo): bool
    {
        try {
            DB::beginTransaction();

            $oldPath = $photo->src;

            $photo->delete();

            if (isset($oldPath) && $photo->id !== Photo::BASE_PHOTO_ID) {
                DeleteFileJob::dispatch($oldPath)->afterCommit();
            }

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
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
