<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table="products";
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function subCategories()
    {
        return $this->belongsTo(Subcategory::class,'subcategory_id');
    }
    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class,'product_id');
    }
}
