<?php

namespace App\Http\Controllers;

use App\AppointmentStatusEnum;
use App\Http\Requests\AppointmentStoreRequest;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentStoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AppointmentStoreRequest $request)
    {
        // Logic to store the appointment would go here.
        $validated = $request->validated();

        // Appointment date must be between 7:00-12:00 or 14:00-18:00
        $appointmentDate = Carbon::parse($validated['appointment_date']);

        if (! ($appointmentDate->between($appointmentDate->copy()->setTime(7, 0), $appointmentDate->copy()->setTime(12, 0)) ||
              $appointmentDate->between($appointmentDate->copy()->setTime(14, 0), $appointmentDate->copy()->setTime(18, 0)))) {
            return response()->json(['error' => 'Appointment date must be between 7:00-12:00 or 14:00-18:00'], 422);
        }

        // Check that the doctor has no appointments at the same time
        $existingAppointment = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $appointmentDate)
            ->exists();

        if ($existingAppointment) {
            return response()->json(['error' => 'Doctor has an existing appointment at this time'], 422);
        }

        // Create the appointment
        $appointment = Appointment::query()
            ->create([
                'doctor_id' => $validated['doctor_id'],
                'patient_id' => $request->user()->id, // Assuming the authenticated user is the patient
                'appointment_date' => $appointmentDate,
                'status' => AppointmentStatusEnum::PENDING,
                'reason' => $validated['reason'],
                'description' => $validated['description'] ?? null,
            ])
            ->loadMissing([
                'doctor:id,name',
                'patient:id,name',
            ]);

        return response()->json($appointment, 201);
    }
}
