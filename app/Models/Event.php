<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillables = [
        'title',
        'description',
        'start_time',
        'end_time',
    ];
    public function class()
    {
        return $this->belongsTo(QG_Class::class, 'class_id');
    }
}
