<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use Notifiable;   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'user_id', 'class', 'photo', 'address', 'phoneno', 'examstatus'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    } 

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
    public function subjectslist()
    {
        return $this->hasMany(SubjectList::class);
    }
    public function exam()
    {
        return $this->hasMany(StudentExam::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
