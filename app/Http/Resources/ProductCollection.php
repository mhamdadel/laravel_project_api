<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array<int|string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'data' => ProductResource::collection($this->collection),
            'current_page' => $this->currentPage(),
            'per_page' => $this->perPage(),
            'total' => $this->total(),
        ];
    }   
}