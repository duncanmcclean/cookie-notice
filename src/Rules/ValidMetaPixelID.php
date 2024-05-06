<?php

namespace DuncanMcClean\CookieNotice\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidMetaPixelID implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($value)) {
            return;
        }

        if (strlen($value) !== 15 || ! is_numeric($value)) {
            $fail('This must be a valid Meta Pixel ID.')->translate();
        }
    }
}