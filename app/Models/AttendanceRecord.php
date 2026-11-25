<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_uuid',
        'lecture_session_id',
        'student_id',
        'lecturer_id',
        'status',
        'scanned_at',
        'schema_version',
        'change_version',
    ];

    protected function casts(): array
    {
        return [
            'record_uuid' => 'uuid',
            'scanned_at' => 'datetime',
            'schema_version' => 'integer',
            'change_version' => 'integer',
        ];
    }

    public function lectureSession(): BelongsTo
    {
        return $this->belongsTo(LectureSession::class);
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