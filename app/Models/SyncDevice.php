<?php

namespace App\Models;

use App\Casts\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SyncDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'lecturer_id',
        'schema_version',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'device_id' => Uuid::class,
            'schema_version' => 'integer',
            'last_seen_at' => 'datetime',
        ];
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function checkpoints(): HasMany
    {
        return $this->hasMany(SyncCheckpoint::class, 'device_id');
    }

    public function pendingPages(): HasMany
    {
        return $this->hasMany(SyncPendingPage::class, 'device_id');
    }
}