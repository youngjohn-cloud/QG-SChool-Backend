<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'day',
        'start_time',
        'end_time',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function class()
    {
        return $this->belongsTo(QG_Class::class, 'class_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function exams()
    {
        return $this->hasMany(Exam::class, 'lesson_id');
    }
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'lesson_id');
    }
    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'lesson_id');
    }
}
