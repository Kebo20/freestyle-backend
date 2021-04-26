<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "categories";
    protected $primaryKey = "idCategory";

    public function products() {
        return $this->hasMany('App\Product', 'idCategory', 'idCategory');
    }
}
