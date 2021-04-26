<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    public function toArray($request)
    {
        return [
            'idProduct' => $this->idProduct,
            'code' => htmlspecialchars($this->code),
            'name' => htmlspecialchars($this->name),
            'name_brand' => htmlspecialchars($this->code . '  ' . $this->name),
            'price' => htmlspecialchars(sprintf('%.2f',(htmlspecialchars($this->price)))),
            'brand' => htmlspecialchars($this->brand),
            'description' => htmlspecialchars($this->description),
            'image' => $this->image?$this->image:"https://sc04.alicdn.com/kf/HTB1nO0xHVXXXXcPXXXXq6xXFXXXj.jpg",
            'units' => htmlspecialchars($this->units),
            'idCategory' => $this->idCategory,
            'category_name' => $this->idCategory ? htmlspecialchars($this->category->name) : '',
            'status' => $this->status
        ];
    }
}
