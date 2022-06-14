<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'class_name', 'subject_name','student_id', 'note_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
