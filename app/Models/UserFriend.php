<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFriend extends Model
{

    protected $fillable = [
        'nickname', 'user_id', 'friend_id', 'group_id'
    ];

}
