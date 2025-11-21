<?php

namespace App\Services;

use App\Interfaces\CoverServiceInterface;
use App\Models\Cover;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Override;
use Throwable;

class CoverService extends Service implements CoverServiceInterface
{
    public function __construct()
    {
        parent::__construct(Cover::class);
    }

    #[Override]
    public function create(array $data): Cover|false
    {
        try {
            // TODO: кидать ивент, чтобы создание падало в очередь
            $path = $this->storeFile($data['src']);

            if (!$path) {
                throw new Exception('Ошибка при сохранении файла');
            }

            $cover = parent::create(['src' => $path]);

            if (!$cover) {
                throw new Exception('Ошибка при создании обложки');
            }

            return $cover;
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
    }

    #[Override]
    public function get(string $id): Cover|false
    {
        return parent::get($id);
    }

    public function update(string $id, array $data): bool
    {
        try {
            $old_path = $this->get($id)->src;

            // TODO: кидать ивент, чтобы удаление падало в очередь
            if ($id !== Cover::BASE_COVER_ID) {
                Storage::disk('public')->delete($old_path);
            }

            // TODO: кидать ивент, чтобы создание падало в очередь
            $path = $this->storeFile($data['src']);

            if (!$path) {
                throw new Exception('Ошибка при сохранении файла');
            }

            $cover = parent::update($id, ['src' => $path]);

            if (!$cover) {
                throw new Exception('Ошибка при создании обложки');
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
        return Storage::disk('public')->putFileAs('covers', $file, $uuid . "." . $fileType);
    }

    public function delete(string $id): bool
    {
        try {
            $path = $this->get($id)->src;

            // TODO: жесткое удаление либо не удалять файл какое-то время
            if (!parent::delete($id)) {
                throw new Exception('Ошибка при удалении файла');
            }

            // TODO: кидать ивент, чтобы падало в очередь
            if ($id !== Cover::BASE_COVER_ID) {
                Storage::disk('public')->delete($path);
            }

            return true;
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
    }
}
