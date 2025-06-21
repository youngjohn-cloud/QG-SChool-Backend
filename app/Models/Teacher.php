<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasFactory, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'teacher_id',
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'gender',
        'address',
        'dob',
        'qualifications',
        'teacher_dp',
        'blood_type',
        'hire_date'
    ];

    // defining the relationship between the teacher and subject, Lesson , Classes
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    public function classes()
    {
        return $this->hasMany(QG_Class::class, 'teacher_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
