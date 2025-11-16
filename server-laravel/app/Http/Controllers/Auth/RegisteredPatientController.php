<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredPatientController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:'.Patient::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Patient::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $patient = Patient::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
            'avatar_id' => 1,
            'experience' => 0,
            'gems' => 0,
        ]);

        event(new Registered($patient));

        Auth::login($patient);

        return response()->noContent();
    }
}
