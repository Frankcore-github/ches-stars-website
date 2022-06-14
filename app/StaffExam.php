<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffExam extends Model
{
    protected $fillable = [
        'class_name', 'subject_name', 'exam_text','file_name', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    } 
}
