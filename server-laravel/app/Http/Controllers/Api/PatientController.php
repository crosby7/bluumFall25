<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PatientProfileResource;
use App\Http\Resources\TaskCompletionResource;
use App\Http\Resources\PatientItemResource;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $this->authorize('viewAny', Patient::class);

        $patients = Patient::all();
        return PatientResource::collection($patients);
    }

    /**
     * Store a newly created patient.
     *
     * @param Request $request
     * @return PatientResource
     */
    public function store(Request $request): PatientResource
    {
        $this->authorize('create', Patient::class);

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:patients'],
            'avatar_id' => ['nullable', 'integer'],
            'experience' => ['nullable', 'integer', 'min:0'],
            'gems' => ['nullable', 'integer', 'min:0'],
        ]);

        // Auto-generate pairing code
        $validated['pairing_code'] = Patient::generatePairingCode();

        $patient = Patient::create($validated);

        return new PatientResource($patient);
    }

    /**
     * Get the authenticated patient.
     *
     * @param Request $request
     * @return PatientResource
     */
    public function show(Request $request): PatientResource
    {
        $this->authorize('view', $request->user());

        return new PatientResource($request->user());
    }

    /**
     * Get the authenticated patient's full profile with stats.
     *
     * @param Request $request
     * @return PatientProfileResource
     */
    public function profile(Request $request): PatientProfileResource
    {
        $this->authorize('view', $request->user());

        return new PatientProfileResource($request->user());
    }

    /**
     * Update the authenticated patient's profile.
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $patient = $request->user();

        $this->authorize('update', $patient);

        $patient->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'patient' => new PatientResource($patient),
        ]);
    }

    /**
     * Remove the specified patient.
     *
     * @param Patient $patient
     * @return JsonResponse
     */
    public function destroy(Patient $patient): JsonResponse
    {
        $this->authorize('delete', $patient);

        $patient->delete();

        return response()->json([
            'message' => 'Patient deleted successfully',
        ]);
    }

    /**
     * Get the authenticated patient's current tasks (scheduled for today).
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function currentTasks(Request $request)
    {
        $patient = $request->user();

        $currentTasks = $patient->taskCompletions()
            ->with(['subscription.task'])
            ->whereDate('scheduled_for', today())
            ->orderBy('scheduled_for')
            ->get();

        return TaskCompletionResource::collection($currentTasks);
    }

    /**
     * Get the authenticated patient's inventory.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function inventory(Request $request)
    {
        $patient = $request->user();

        $inventory = $patient->inventory()->with('item')->get();

        return PatientItemResource::collection($inventory);
    }
}
