<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class TeacherAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'student_id',
    ];
}
