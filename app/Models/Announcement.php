<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'title',
        'description',
        'date',
    ];
    public function class()
    {
        return $this->belongsTo(QG_Class::class, 'class_id');
    }
}
