<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'type' => 'users',
            'id' => $this->id(),
            'attributes' => [
                'name' => $this->name(),
                'email' => $this->email(),
                'phone_number' => $this->phoneNumber(),
                'is_admin' => $this->isAdmin(),
            ],
            'links' => [
                'self' => route('users.show', $this->id()),
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
        return $response->header('Accept', 'Application/json');
    }
}
