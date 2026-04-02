<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\InvokableRule;

class StrongPassword implements InvokableRule
{
    public function __invoke(string $attribute, mixed $value, Closure $fail): void
    {
        $password = (string) $value;

        if (strlen($password) < 8) {
            $fail('Password must be at least 8 characters.');
        }

        if (! preg_match('/[A-Z]/', $password)) {
            $fail('Password must contain at least one uppercase letter.');
        }

        if (! preg_match('/[0-9]/', $password)) {
            $fail('Password must contain at least one number.');
        }

        if (! preg_match('/[@$!%*?&_-]/', $password)) {
            $fail('Password must contain at least one special character.');
        }

        $blocklist = [
            'password',
            'password123',
            'admin123',
            'gervacios',
            'cafe123',
            '12345678',
            'qwerty123',
            'letmein1',
            'welcome1',
            'Password1',
            'Admin1234',
        ];

        if (in_array($password, $blocklist, true)) {
            $fail('This password is too common. Please choose a stronger one.');
        }
    }
}
