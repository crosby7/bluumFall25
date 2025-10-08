<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NurseResource;
use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class NurseController extends Controller
{
    /**
     * Display a listing of nurses.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $nurses = Nurse::all();
        return NurseResource::collection($nurses);
    }

    /**
     * Store a newly created nurse.
     *
     * @param Request $request
     * @return NurseResource
     */
    public function store(Request $request): NurseResource
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:nurses'],
            'password' => ['required', Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $nurse = Nurse::create($validated);

        return new NurseResource($nurse);
    }

    /**
     * Display the specified nurse.
     *
     * @param Nurse $nurse
     * @return NurseResource
     */
    public function show(Nurse $nurse): NurseResource
    {
        return new NurseResource($nurse);
    }

    /**
     * Update the specified nurse.
     *
     * @param Request $request
     * @param Nurse $nurse
     * @return NurseResource
     */
    public function update(Request $request, Nurse $nurse): NurseResource
    {
        $validated = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:nurses,email,' . $nurse->id],
            'password' => ['sometimes', Password::defaults()],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $nurse->update($validated);

        return new NurseResource($nurse);
    }

    /**
     * Remove the specified nurse.
     *
     * @param Nurse $nurse
     * @return JsonResponse
     */
    public function destroy(Nurse $nurse): JsonResponse
    {
        $nurse->delete();

        return response()->json([
            'message' => 'Nurse deleted successfully',
        ]);
    }
}
