<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AppointmentStoreRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;

class AppointmentController extends ApiController
{
    public function index()
    {
        return AppointmentResource::collection(Appointment::all());
    }

    public function store(AppointmentStoreRequest $request)
    {
        $appointment = Appointment::create($request->validated());

        return new AppointmentResource($appointment);
    }

    public function show(string $id)
    {
        $appointment = Appointment::findOrFail($id);

        return new AppointmentResource($appointment);
    }

    public function update(AppointmentUpdateRequest $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->validated());

        return new AppointmentResource($appointment);
    }

    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->noContent();
    }
}
