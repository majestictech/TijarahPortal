<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreType extends Model
{
    protected $table = 'mas_storetype';
    public $timestamps = true;

    protected $fillable = [
		'id','name',
	];
}
