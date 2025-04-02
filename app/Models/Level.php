<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillables = [
        'name',
    ];
    public function student()
    {
        return $this->hasMany(Student::class, 'level_id');
    }
    public function Class()
    {
        return $this->hasMany(QG_Class::class, 'level_id');
    }
}
