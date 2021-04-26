<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'idCategory'=>$this->idCategory,
            'name'=>htmlspecialchars($this->name),
            // 'products'=>Product::collection($this->products),
            'status'=>$this->status
        ];
    }
}
