<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsFeed extends Model
{
    protected $fillable = [
        'file_name', 'file_caption'
    ];
}
