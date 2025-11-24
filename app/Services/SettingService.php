<?php

namespace App\Services;

use App\Interfaces\SettingServiceInterface;
use App\Models\Setting;
use Override;

final class SettingService extends Service implements SettingServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct()
    {
        parent::__construct(Setting::class);
    }

    #[Override]
    public function create(array $data): Setting|false
    {
        return parent::create($data);
    }

    #[Override]
    public function get(string $id): Setting|false
    {
        return parent::get($id);
    }
}
