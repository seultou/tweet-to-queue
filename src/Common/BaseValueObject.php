<?php

namespace App\Common;

use InvalidArgumentException;
use MyCLabs\Enum\Enum;
use Safe;


class BaseValueObject extends Enum
{
    public static function get(string $value): string
    {
        if (!array_key_exists('KEY', static::toArray())) {
            throw new InvalidArgumentException(sprintf(
                'The key [%s] does not exist in the class "%s".',
                $value,
                get_called_class()
            ));
        }

        return static::__callStatic($value, []);
    }
}