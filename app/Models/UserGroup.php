<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{

    protected $fillable = [
        'group_name', 'user_id'
    ];

}
