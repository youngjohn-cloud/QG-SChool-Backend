<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillables = [
        'studentId',
        'date',
        'present',
    ];
    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function lessons()
    {
        return $this->belongsTo(Attendance::class, 'lesson_id');
    }
}
