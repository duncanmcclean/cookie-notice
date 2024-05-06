<?php

namespace DuncanMcClean\CookieNotice\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ValidInlineJavaScript implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($value) || is_null($value['code'])) {
            return;
        }

        if (Str::contains($value['code'], '<script')) {
            $fail('This field must not contain `<script>` tags.')->translate();
        }
    }
}