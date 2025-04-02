<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level'];
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'subject_id');
    }
    public function teacher()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject');
    }
}
