<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'firstname',
        'middlename',
        'lastname',
        'email',
        'password',
        'phone',
        'gender',
        'blood_type',
        'dob',
        'home_address',
        'department',
        'student_dp',
        'guardian_id',
        'class_id',
        'level_id',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function guardian()
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }
    public function class()
    {
        return $this->belongsTo(QG_Class::class, 'class_id');
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
    public function result()
    {
        return $this->hasMany(Result::class, 'student_id');
    }
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'student_exam');
    }
    public function assignments()
    {
        return $this->belongsToMany(Assignment::class, 'student__assignment');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
