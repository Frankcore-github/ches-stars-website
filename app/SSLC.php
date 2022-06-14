<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SSLC extends Model
{
    protected $fillable = [
        'no_of_student', 'no_of_passed_student', 'year'
    ];
}

