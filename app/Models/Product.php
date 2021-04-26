<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

     //
     protected $table = "products";
     protected $primaryKey = "idProduct";
 
     public function category() {
         return $this->hasOne(Category::class, 'idCategory', 'idCategory');
     }
 
     public function lots() {
         return $this->hasMany('App\Lot', 'idProduct', 'idProduct');
     }
 
     public function purchasesDetail() {
         return $this->hasMany('App\PurchaseDetail', 'idProduct', 'idProduct');
     }
 
     public function salesDetail() {
         return $this->hasMany('App\SaleDetail', 'idProduct', 'idProduct');
     }
}
