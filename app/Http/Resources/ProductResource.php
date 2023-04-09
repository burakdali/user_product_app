<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public static $wrap = 'products';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'type' => 'products',
            'id' => $this->id(),
            'attributes' => [
                'name' => $this->name(),
                'slug' => $this->slug(),
                'description' => $this->description(),
                'image' => $this->image(),
                'created_at' => $this->created_at,
            ],
            'links' => [
                'self' => route('products.show', $this->slug()),
            ]
        ];
    }
    public function with($request)
    {
        return [
            'status' => 'success',
        ];
    }
    public function withResponse($request, $response)
    {
        $response->header('Accept', 'application/json');
    }
}
