<?php

namespace App\Http\Requests;

use App\AppointmentStatusEnum;
use App\Models\Appointment;
use App\RoleEnum;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AppointmentsConfirmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user->role_id === RoleEnum::DOCTOR && Appointment::query()
            ->where('id', $this->integer('appointment_id'))
            ->where('doctor_id', $user->id)
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appointment_id' => [
                'required',
                Rule::exists('appointments', 'id')->where(fn (Builder $query) => $query->where('doctor_id', $this->user()->id))
                    ->where('status', AppointmentStatusEnum::PAID),
            ],
            "status" => [
                'required',
                Rule::in([
                    AppointmentStatusEnum::CONFIRMED->value,
                    AppointmentStatusEnum::CANCELLED->value,
                ]),
            ],
        ];
    }
}
