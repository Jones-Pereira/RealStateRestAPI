<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CityStoreRequest;
use App\Http\Requests\CityUpdateRequest;
use App\Http\Resources\CityResource;
use App\Models\City;

class CityController extends ApiController
{
    public function index()
    {
        return CityResource::collection(City::all());
    }

    public function store(CityStoreRequest $request)
    {
        $city = City::create($request->validated());

        return new CityResource($city);
    }

    public function show(string $id)
    {
        $city = City::findOrFail($id);

        return new CityResource($city);
    }

    public function update(CityUpdateRequest $request, string $id)
    {
        $city = City::findOrFail($id);
        $city->update($request->validated());

        return new CityResource($city);
    }

    public function destroy(string $id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return response()->noContent();
    }
}
