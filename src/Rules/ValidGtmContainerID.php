<?php

namespace DuncanMcClean\CookieNotice\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ValidGtmContainerID implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($value)) {
            return;
        }

        if (! Str::startsWith($value, 'GTM-')) {
            $fail('This must be a valid Google Tag Manager Container ID.')->translate();
        }
    }
}
