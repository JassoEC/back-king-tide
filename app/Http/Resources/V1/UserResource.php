<?php

namespace App\Http\Resources\V1;

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
            'name'               => $this->name,
            'lastName'           => $this->last_name,
            'surName'            => $this->sur_name,
            'rfc'                => $this->rfc,
            'birthday'           => $this->birthday,
            'profilePicture'     => $this->profilePicture,
            'profilePicturePath' => $this->profilePicturePath,
            'updatedAt'          => $this->updatedAt,
            'files'              => FileResource::collection($this->files),
        ];
    }
}
