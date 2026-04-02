<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhilippinePhone implements Rule
{
    public function passes($attribute, $value): bool
    {
        return preg_match('/^(\+63|09)\d{9}$/', $value);
    }

    public function message(): string
    {
        return 'Enter a valid PH number (+63XXXXXXXXX or 09XXXXXXXXX).';
    }
}
