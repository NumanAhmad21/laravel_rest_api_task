<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'brand_id',
        'product_name',
        'product_code',
        'subcategory_name',
        'product_quantity',
        'product_details',
        'product_color',
        'product_size',
        'selling_price',
        'discount_price',
        'status',
    ];
}
