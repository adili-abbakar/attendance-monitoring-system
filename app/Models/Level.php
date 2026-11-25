<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_uuid',
        'name',
    ];

    protected function casts(): array
    {
        return [
            'record_uuid' => 'uuid',
        ];
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}