<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    /**
     * Display a listing of items.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $this->authorize('viewAny', Item::class);

        $items = Item::all();
        return ItemResource::collection($items);
    }

    /**
     * Store a newly created item.
     *
     * @param Request $request
     * @return ItemResource
     */
    public function store(Request $request): ItemResource
    {
        $this->authorize('create', Item::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        $item = Item::create($validated);

        return new ItemResource($item);
    }

    /**
     * Display the specified item.
     *
     * @param Item $item
     * @return ItemResource
     */
    public function show(Item $item): ItemResource
    {
        $this->authorize('view', $item);

        return new ItemResource($item);
    }

    /**
     * Update the specified item.
     *
     * @param Request $request
     * @param Item $item
     * @return ItemResource
     */
    public function update(Request $request, Item $item): ItemResource
    {
        $this->authorize('update', $item);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'integer', 'min:0'],
            'image' => ['sometimes', 'nullable', 'string'],
            'category' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $item->update($validated);

        return new ItemResource($item);
    }

    /**
     * Remove the specified item.
     *
     * @param Item $item
     * @return JsonResponse
     */
    public function destroy(Item $item): JsonResponse
    {
        $this->authorize('delete', $item);

        $item->delete();

        return response()->json([
            'message' => 'Item deleted successfully',
        ]);
    }
}
