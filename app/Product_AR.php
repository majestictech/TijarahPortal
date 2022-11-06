<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Product_AR extends Model
{
    protected $table = 'products_ar';
	protected $fillable = [
	    'productId',
        'name',
        'description'
    ];
}
