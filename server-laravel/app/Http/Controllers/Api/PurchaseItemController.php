<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseItemRequest;
use App\Http\Resources\PatientItemResource;
use App\Models\Item;
use App\Models\PatientItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PurchaseItemController extends Controller
{
    /**
     * Purchase an item for the authenticated patient.
     *
     * @param PurchaseItemRequest $request
     * @param Item $item
     * @return PatientItemResource|JsonResponse
     */
    public function purchase(PurchaseItemRequest $request, Item $item): PatientItemResource|JsonResponse
    {
        $this->authorize('purchase', $item);

        $patient = $request->user();

        // Check if patient already owns the item
        $alreadyOwned = PatientItem::where('patient_id', $patient->id)
            ->where('item_id', $item->id)
            ->exists();

        if ($alreadyOwned) {
            return response()->json([
                'message' => 'You already own this item.',
            ], 422);
        }

        // Check if patient has enough gems
        if ($patient->gems < $item->price) {
            return response()->json([
                'message' => 'Insufficient gems. You need ' . $item->price . ' gems but only have ' . $patient->gems . '.',
            ], 422);
        }

        // Use transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Deduct gems from patient
            $patient->gems -= $item->price;
            $patient->save();

            // Add item to patient's inventory
            $patientItem = PatientItem::create([
                'patient_id' => $patient->id,
                'item_id' => $item->id,
                'equipped' => false,
            ]);

            DB::commit();

            // Load the item relationship for the resource
            $patientItem->load('item');

            return new PatientItemResource($patientItem);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Purchase failed. Please try again.',
            ], 500);
        }
    }
}
