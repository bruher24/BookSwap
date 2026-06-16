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
use Throwable;

final class CoverService implements CoverServiceInterface
{
    public function create(array $data): Cover|false
    {
        try {
            DB::beginTransaction();
            $path = $this->storeFile($data['file']);

            if ($path === false) {
                throw new Exception('Ошибка при сохранении файла');
            }

            $cover = new Cover(['src' => $path, 'user_id' => $data['user_id']]);

            if (!$cover->save()) {
                throw new Exception("Ошибка при создании обложки");
            }

            DB::commit();
            return $cover->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function get(string $id): Cover|false
    {
        try {
            return Cover::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

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

    public function delete(Cover $cover): bool
    {
        try {
            DB::beginTransaction();

            $oldPath = $cover->src;

            $cover->delete();

            if (isset($oldPath) && $cover->id !== Cover::BASE_COVER_ID) {
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
        return Storage::disk('public')->putFileAs('covers', $file, $uuid . "." . $fileType);
    }
}
