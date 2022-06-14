<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Note extends Model
{
    protected $fillable = [
        'class_name', 'subject_name','notes_text', 'file_name', 'chapter_name', 'user_id', 'url'
    ];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
