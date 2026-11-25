<?php

namespace App\Models;

use App\Casts\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'record_uuid',
        'staff_number',
        'phone',
        'gender',
        'date_of_birth',
        'passport',
        'address',
        'schema_version',
        'change_version',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'record_uuid' => Uuid::class,
            'schema_version' => 'integer',
            'change_version' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'lecturer_id');
    }

    public function lectureSessions(): HasMany
    {
        return $this->hasMany(LectureSession::class, 'lecturer_id');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'lecturer_id');
    }

    public function enrolments(): HasMany
    {
        return $this->hasMany(Enrolment::class, 'lecturer_id');
    }

    public function syncDevices(): HasMany
    {
        return $this->hasMany(SyncDevice::class, 'lecturer_id');
    }

    public function changeFeed(): HasMany
    {
        return $this->hasMany(ChangeFeed::class, 'lecturer_id');
    }

    public function changeFeedCounters(): HasMany
    {
        return $this->hasMany(ChangeFeedCounter::class, 'lecturer_id');
    }

    public function changeFeedEventIds(): HasMany
    {
        return $this->hasMany(ChangeFeedEventId::class, 'lecturer_id');
    }

    public function deletionMarkers(): HasMany
    {
        return $this->hasMany(DeletionMarker::class, 'lecturer_id');
    }

    public function admissionReservations(): HasMany
    {
        return $this->hasMany(StudentAdmissionReservation::class, 'lecturer_id');
    }
}