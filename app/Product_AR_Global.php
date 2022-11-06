<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Product_AR_Global extends Model
{
    protected $table = 'products_ar_global';
	protected $fillable = [
	    'productId',
        'name',
        'description'
    ];
}
