<?php

namespace App\Http\Requests;

use App\Rules\PhilippinePhone;
use App\Services\BookingGuardService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * StoreBookingRequest
 *
 * Validates kiosk booking form input.
 * Includes phone number deduplication check via BookingGuardService.
 */
class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'table_id' => ['required', 'integer', 'exists:tables,id'],
            'customer_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\.]+$/'],
            'customer_phone' => ['required', new PhilippinePhone],
            'party_size' => ['required', 'integer', 'min:1', 'max:20'],
            'priority_type' => ['required', Rule::in(['none', 'pwd', 'pregnant', 'senior'])],
            'honeypot' => ['max:0'], // must be empty (anti-spam)
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.regex' => 'Name may only contain letters, spaces, hyphens, and periods.',
            'honeypot.max' => 'Invalid submission.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $phone = $this->input('customer_phone');

            if (app(BookingGuardService::class)->hasActiveEntry($phone)) {
                $validator->errors()->add('customer_phone', 'This phone number already has an active booking or queue entry.');
            }
        });
    }
}
