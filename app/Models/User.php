<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dob',
        'guardian_name',
        'guardian_phone',
        'community_tribe',
        'current_camp_location',
        'preferred_language',
        'enrollment_date',
        'status',
        'phone',
        'subject_specialization',
        'qualification',
        'experience_years',
        'assigned_community',
        'is_volunteer',
        'guardian_of',
        'class_id',
        'teacher_id',
        'completed_lessons',
        'achievements',
        'quiz_results',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'dob' => 'date',
            'enrollment_date' => 'date',
            'status' => 'boolean',
            'experience_years' => 'integer',
            'is_volunteer' => 'boolean',
            'guardian_of' => 'array',
            'completed_lessons' => 'array',
            'achievements' => 'array',
            'quiz_results' => 'array',
        ];
    }

    /**
     * Get the identifier stored in the JWT subject claim.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function classesTaught(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
    }

    public function assignedStudents(): HasMany
    {
        return $this->hasMany(self::class, 'teacher_id');
    }

    public function lessonsTaught(): HasMany
    {
        return $this->hasMany(Lesson::class, 'teacher_id');
    }

    public function assessmentsTaught(): HasMany
    {
        return $this->hasMany(Assessment::class, 'teacher_id');
    }

    public function attendanceMarked(): HasMany
    {
        return $this->hasMany(Attendance::class, 'marked_by');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function schoolsHeaded(): HasMany
    {
        return $this->hasMany(School::class, 'head_teacher_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function assignedTeacher(): BelongsTo
    {
        return $this->belongsTo(self::class, 'teacher_id');
    }
}
