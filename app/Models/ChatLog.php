<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{

    protected $fillable = [
        'type', 'from_user_id', 'to_user_id', 'content', 'group_id'
    ];

}
