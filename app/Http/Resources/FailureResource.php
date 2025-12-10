<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class FailureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        $statusCode = (int)($this['statusCode'] ?? 400);
        $errors = (array)($this['errors'] ?? []);

        return [
            'statusCode' => $statusCode,
            'errors' => $errors,
        ];
    }
}
