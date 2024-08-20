<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StateStoreRequest;
use App\Http\Requests\StateUpdateRequest;
use App\Http\Resources\StateResource;
use App\Models\State;

class StateController extends ApiController
{
    public function index()
    {
        return StateResource::collection(State::all());
    }

    public function store(StateStoreRequest $request)
    {
        $state = State::create($request->validated());

        return new StateResource($state);
    }

    public function show(string $id)
    {
        $state = State::findOrFail($id);

        return new StateResource($state);
    }

    public function update(StateUpdateRequest $request, string $id)
    {
        $state = State::findOrFail($id);
        $state->update($request->validated());

        return new StateResource($state);
    }

    public function destroy(string $id)
    {
        $state = State::findOrFail($id);
        $state->delete();

        return response()->noContent();
    }
}
