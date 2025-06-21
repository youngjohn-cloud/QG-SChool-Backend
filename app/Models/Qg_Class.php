<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qg_Class extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'level_id',
        'teacher_id',
    ];
    // Realtionship to other models 
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'class_id');
    }
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
    public function teachers()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
    public function events()
    {
        return $this->hasMany(Event::class, 'class_id');
    }
    public function announcement()
    {
        return $this->hasMany(Announcement::class, 'class_id');
    }
}
