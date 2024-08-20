<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AgentStoreRequest;
use App\Http\Requests\AgentUpdateRequest;
use App\Http\Resources\AgentResource;
use App\Models\Agent;

class AgentController extends ApiController
{
    public function index()
    {
        return AgentResource::collection(Agent::all());
    }

    public function store(AgentStoreRequest $request)
    {
        $agent = Agent::create($request->validated());

        return new AgentResource($agent);
    }

    public function show(string $id)
    {
        $agent = Agent::findOrFail($id);

        return new AgentResource($agent);
    }

    public function update(AgentUpdateRequest $request, string $id)
    {
        $agent = Agent::findOrFail($id);
        $agent->update($request->validated());

        return new AgentResource($agent);
    }

    public function destroy(string $id)
    {
        $agent = Agent::findOrFail($id);
        $agent->delete();

        return response()->noContent();
    }
}
