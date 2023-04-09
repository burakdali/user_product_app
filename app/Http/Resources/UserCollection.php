<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'users' => $this->collection,
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
        $request->header('Accept', 'application/json');
    }
}
