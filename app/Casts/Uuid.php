<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Uuid implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        try {
            return RamseyUuid::fromString($value);
        } catch (UnsatisfiedDependencyException) {
            return $value;
        }
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \Ramsey\Uuid\UuidInterface) {
            return $value->toString();
        }

        return (string) $value;
    }
}