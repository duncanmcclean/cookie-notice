<?php

namespace DuncanMcClean\CookieNotice\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidGtmContainerID implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($value)) {
            return;
        }

        if (! preg_match('/^GTM-[A-Z0-9]+$/', $value)) {
            $fail('This must be a valid Google Tag Manager Container ID.')->translate();
        }
    }
}
