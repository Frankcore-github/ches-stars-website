<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'user_id', 'photo', 'classes','address','phoneno'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    } 
    public function subjectslist()
    {
        return $this->hasMany(SubjectList::class);
    } 

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    } 
  
    
}
