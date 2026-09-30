<?php

namespace App\Support;

use Illuminate\Validation\ValidationException;

final class Rule
{
    public static function ensure(bool $condition, string $message, string $field = 'status'): void
    {
        if (! $condition) {
            throw ValidationException::withMessages([$field => $message]);
        }
    }
}
