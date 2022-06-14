<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassTeacher extends Model
{
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'class'
    ];
}
