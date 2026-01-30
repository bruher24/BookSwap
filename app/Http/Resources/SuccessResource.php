<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class SuccessResource extends JsonResource
{
    public function __construct(array $resource = null)
    {
        if (!isset($resource)) {
            $resource = [];
        }

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        $statusCode = (int)($this['statusCode'] ?? 200);
        $data = parent::toArray($request);

        return [
            'statusCode' => $statusCode,
            'data' => $data,
        ];
    }
}
