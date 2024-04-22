<?php

namespace App\Casts;

use InvalidArgumentException;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

use App\ValueObjects\SouthAfricanId;

class AsSouthAfricanId implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): SouthAfricanId
    {
        return new SouthAfricanId($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (! $value instanceof SouthAfricanId) {
            throw new InvalidArgumentException('The value is not an instance of ' . SouthAfricanId::class . '.');
        }

        return [$key => strval($value)];
    }
}
