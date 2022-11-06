<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $table = 'user_logs';
    protected $fillable = [
        'subject', 'userId'
    ];

}
