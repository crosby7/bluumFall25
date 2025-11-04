<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PatientAuthController extends Controller
{
    /**
     * Handle patient pairing and return Sanctum token
     */
    public function login(Request $request)
    {
        $request->validate([
            'pairing_code' => 'required|string|size:6',
            'device_identifier' => 'nullable|string',
        ]);

        $patient = Patient::where('pairing_code', $request->pairing_code)->first();

        if (!$patient) {
            throw ValidationException::withMessages([
                'pairing_code' => ['The provided pairing code is incorrect.'],
            ]);
        }

        // Update pairing information
        $patient->update([
            'paired_at' => now(),
            'device_identifier' => $request->device_identifier,
        ]);

        // Revoke all existing tokens for this patient (single device login)
        // Remove this line if you want multi-device support
        $patient->tokens()->delete();

        // Create a new token
        $token = $patient->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'patient' => PatientResource::make($patient),
        ]);
    }

    /**
     * Handle patient logout
     */
    public function logout(Request $request)
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get authenticated patient info
     */
    public function me(Request $request)
    {
        return PatientResource::make($request->user());
    }
}
