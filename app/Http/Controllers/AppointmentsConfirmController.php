<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentsConfirmRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentsConfirmController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AppointmentsConfirmRequest $request)
    {
        $appointment = Appointment::query()
            ->where('id', $request->integer('appointment_id'))
            ->where('doctor_id', $request->user()->id)
            ->firstOrFail();

        $appointment->update([
            'status' => $request->input('status'),
        ]);

        return response()->json([
            'message' => 'Appointment status updated successfully.',
            'appointment' => $appointment,
        ]);
    }
}
