<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentVote extends Model
{
    protected $fillable = [
        'user_id', 'name', 'class_name', 'vote'
    ];
}
