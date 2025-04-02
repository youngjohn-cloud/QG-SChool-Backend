<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillables = [
        'teacher_id',
        'name',
        'description',
    ];
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
