<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    protected $fillable = [
        'class_name', 'subject_name', 'file_name', 'status', 'student_id'
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
