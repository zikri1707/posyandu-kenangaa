<?php

namespace App\Http\Controllers\API;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PatientApiController extends Controller
{
    public function index()
    {
        $patients = Patient::all();
        return view('patients.index', compact('patients'));
    }

    public function store(Request $request)
    {
        Patient::create($request->all());
        return redirect()->route('patients.index');
    }
    
    public function show($id)
    {
        // Cek apakah pengguna yang sedang login adalah pemilik data
        $user = Auth::user();

        // Cek apakah pasien yang diminta milik pengguna yang sedang login
        $patient = Patient::findOrFail($id);

        if ($patient->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized access.'], 403); // Unauthorized if they try to access someone else's data
        }

        return response()->json($patient);
    }

    public function update(Request $request, $id)
    {
        // Cek apakah pengguna yang sedang login adalah pemilik data
        $user = Auth::user();

        // Cek apakah pasien yang diminta milik pengguna yang sedang login
        $patient = Patient::findOrFail($id);

        if ($patient->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized access.'], 403); // Unauthorized if they try to update someone else's data
        }

        $patient->update($request->all()); // Update patient data
        return response()->json($patient);
    }
}
