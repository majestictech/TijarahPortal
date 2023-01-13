<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProductInventoryBatch extends Authenticatable
{
    protected $table = 'productInventoryBatch';
}
