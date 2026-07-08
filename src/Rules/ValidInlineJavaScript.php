<?php

namespace DuncanMcClean\CookieNotice\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ValidInlineJavaScript implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $code = is_array($value) ? $value['code'] ?? null : $value;

        if (is_null($code)) {
            return;
        }

        if (Str::contains($code, '<script')) {
            $fail('This field must not contain `<script>` tags.')->translate();
        }
    }
}
