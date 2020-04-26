<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = [
        'type', 'from_user_id', 'to_user_id', 'status', 'remark', 'group_id'
    ];

}
