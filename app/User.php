<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'usertype'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function staff()
    {
        return $this->hasOne(Staff::class);
    } 
    
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function staffexam()
    {
        return $this->hasMany(StaffExam::class);
    }

    public function studentexam()
    {
        return $this->hasMany(StudentExam::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable'); 
    }

}
