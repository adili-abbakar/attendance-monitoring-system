<?php

namespace App\Models;

use App\Casts\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'record_uuid',
        'admission_number',
        'phone',
        'gender',
        'date_of_birth',
        'passport',
        'address',
        'status',
        'schema_version',
        'change_version',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'record_uuid' => Uuid::class,
            'status' => 'boolean',
            'schema_version' => 'integer',
            'change_version' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrolments(): HasMany
    {
        return $this->hasMany(Enrolment::class, 'student_id');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'student_id');
    }
}