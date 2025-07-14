<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Appointment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'doctor_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn (Builder $query) => $query->where('role_id', '=', \App\RoleEnum::DOCTOR)),
            ],
            'appointment_date' => [
                'required',
                'date',
                'after:now',
            ],
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
