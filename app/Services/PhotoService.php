<?php

namespace App\Services;

use App\Interfaces\PhotoServiceInterface;
use App\Models\Photo;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Override;
use Throwable;

final class PhotoService extends Service implements PhotoServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct()
    {
        parent::__construct(Photo::class);
    }

    #[Override]
    public function create(array $data): Photo|false
    {
        try {
            // TODO: кидать ивент, чтобы создание падало в очередь
            $path = $this->storeFile($data['src']);

            if ($path === false) {
                throw new Exception('Ошибка при сохранении файла');
            }

            $cover = parent::create(['src' => $path]);

            if (!$cover) {
                throw new Exception('Ошибка при создании фото');
            }

            return $cover;
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
    }

    #[Override]
    public function get(string $id): Photo|false
    {
        return parent::get($id);
    }

    #[Override]
    public function update(string $id, array $data): bool
    {
        try {
            $old_file = $this->get($id);

            // TODO: кидать ивент, чтобы удаление падало в очередь
            if ($id !== Photo::BASE_PHOTO_ID && $old_file instanceof Photo) {
                Storage::disk('public')->delete($old_file->src);
            }

            // TODO: кидать ивент, чтобы создание падало в очередь
            $path = $this->storeFile($data['src']);

            if ($path === false) {
                throw new Exception('Ошибка при сохранении файла');
            }

            $cover = parent::update($id, ['src' => $path]);

            if (!$cover) {
                throw new Exception('Ошибка при создании фото');
            }

            return true;
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
    }

    private function storeFile(UploadedFile $file): false|string
    {
        $uuid = Str::uuid()->toString();
        $fileType = $file->getClientOriginalExtension();
        return Storage::disk('public')->putFileAs('avatars', $file, $uuid . "." . $fileType);
    }

    #[Override]
    public function delete(string $id): bool
    {
        try {
            $file = $this->get($id);

            if (!$file instanceof Photo) {
                throw new Exception('Файл не найден');
            }

            // TODO: жесткое удаление либо не удалять файл какое-то время
            if (!parent::delete($id)) {
                throw new Exception('Ошибка при удалении файла');
            }

            // TODO: кидать ивент, чтобы падало в очередь
            if ($id !== Photo::BASE_PHOTO_ID) {
                Storage::disk('public')->delete($file->src);
            }

            return true;
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
    }
}
