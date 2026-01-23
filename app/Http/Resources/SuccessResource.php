<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class SuccessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        $statusCode = $this['statusCode'] ?? 200;
        $data = parent::toArray($request);

        return [
            'statusCode' => $statusCode,
            'data' => $data,
        ];
    }
}
