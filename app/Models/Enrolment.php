<?php

namespace App\Models;

use App\Casts\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrolment extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_uuid',
        'course_id',
        'student_id',
        'lecturer_id',
        'enrolled_at',
        'schema_version',
        'change_version',
    ];

    protected function casts(): array
    {
        return [
            'record_uuid' => Uuid::class,
            'enrolled_at' => 'datetime',
            'schema_version' => 'integer',
            'change_version' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}