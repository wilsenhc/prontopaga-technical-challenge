<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentsIndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $appointments = Appointment::query()
            ->with([
                'patient:id,name',
            ])
            ->where('doctor_id', Auth::user()->id)
            ->get();

        return response()->json($appointments);
    }
}
