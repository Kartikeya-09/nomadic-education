<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'title',
        'class_id',
        'teacher_id',
        'date',
        'max_score',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'max_score' => 'integer',
        ];
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
