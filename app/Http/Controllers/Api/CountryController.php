<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CountryStoreRequest;
use App\Http\Requests\CountryUpdateRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;

class CountryController extends ApiController
{
    public function index()
    {
        return CountryResource::collection(Country::all());
    }

    public function store(CountryStoreRequest $request)
    {
        $country = Country::create($request->validated());

        return new CountryResource($country);
    }

    public function show(string $id)
    {
        $country = Country::findOrFail($id);

        return new CountryResource($country);
    }

    public function update(CountryUpdateRequest $request, string $id)
    {
        $country = Country::findOrFail($id);
        $country->update($request->validated());

        return new CountryResource($country);
    }

    public function destroy(string $id)
    {
        $country = Country::findOrFail($id);
        $country->delete();

        return response()->noContent();
    }
}
