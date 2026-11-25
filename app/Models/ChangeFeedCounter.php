<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangeFeedCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'collection_name',
        'next_sequence',
    ];

    protected function casts(): array
    {
        return [
            'next_sequence' => 'integer',
        ];
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}