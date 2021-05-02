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
            'price_old' => htmlspecialchars(sprintf('%.2f',(htmlspecialchars($this->price_old)))),
            'brand' => htmlspecialchars($this->brand),
            'description' => htmlspecialchars($this->description),
            'image' => $this->image?$this->image:"",
            'image_real' => $this->image?$this->image:"",

            'novelty' => $this->novelty==1?true:false,
            'units' => htmlspecialchars($this->units),
            'idCategory' => $this->idCategory,
            'active' => $this->active,
            'category_name' => $this->idCategory ? htmlspecialchars($this->category->name) : '',
            'status' => $this->status
        ];
    }
}
