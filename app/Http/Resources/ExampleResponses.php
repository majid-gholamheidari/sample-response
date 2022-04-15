<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExampleResponses extends JsonResource
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
            'title' => $this->title ?? "",
            'category' => $this->category ?? "",
            'usage' => $this->usage ?? 0,
            'url' => url("/show-json/$this->url")
        ];
    }
}
