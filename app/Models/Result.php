<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'assignment_id',
        'exam_id',
        'score',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
