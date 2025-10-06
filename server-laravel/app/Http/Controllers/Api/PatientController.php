<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PatientProfileResource;
use App\Http\Resources\TaskCompletionResource;
use App\Http\Resources\PatientItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    /**
     * Get the authenticated patient.
     *
     * @param Request $request
     * @return PatientResource
     */
    public function show(Request $request): PatientResource
    {
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

        $patient->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'patient' => new PatientResource($patient),
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
