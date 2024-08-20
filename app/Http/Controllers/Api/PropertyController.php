<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PropertyStoreRequest;
use App\Http\Requests\PropertyUpdateRequest;
use App\Http\Resources\PropertyResource;
use App\Models\Property;

class PropertyController extends ApiController
{
    public function index()
    {
        return PropertyResource::collection(Property::all());
    }

    public function store(PropertyStoreRequest $request)
    {
        $property = Property::create($request->validated());

        return new PropertyResource($property);
    }

    public function show(string $id)
    {
        $property = Property::findOrFail($id);

        return new PropertyResource($property);
    }

    public function update(PropertyUpdateRequest $request, string $id)
    {
        $property = Property::findOrFail($id);
        $property->update($request->validated());

        return new PropertyResource($property);
    }

    public function destroy(string $id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return response()->noContent();
    }
}
