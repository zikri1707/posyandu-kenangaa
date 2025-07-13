<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;

class PatientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $patientId = $request->route('patient') ?? $request->route('id');

        // Verifikasi patient exists
        if (!Patient::find($patientId)) {
            abort(404, 'Patient not found');
        }

        // Jika user adalah pasien yang bersangkutan
        if ($user->role === 'patient' && $user->patient_id != $patientId) {
            Log::warning("Unauthorized patient data access attempt by user ID {$user->id}");
            abort(403, 'You can only access your own patient data');
        }

        // Jika user adalah staff medis atau admin
        if (!in_array($user->role, ['medical', 'admin', 'superadmin'])) {
            Log::warning("Unauthorized patient data access attempt by user ID {$user->id}");
            abort(403, 'Unauthorized access to patient data');
        }

        return $next($request);
    }
}