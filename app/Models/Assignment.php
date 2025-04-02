<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillables = [
        'lesson_id',
        'title',
        'start_date',
        'due_date',
    ];

    public function result()
    {
        return $this->hasMany(Result::class, 'assignment_id');
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student__assignment');
    }
    public function Lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
}
