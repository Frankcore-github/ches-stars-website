<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectList extends Model
{
    
    protected $fillable = [
        'class_name', 'subject_name', 'background_color', 'staff_id'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    } 
    
    public function students()
    {
        return $this->belongsTo(Student::class);
    } 
}
