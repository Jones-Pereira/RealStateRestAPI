<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ImageStoreRequest;
use App\Http\Requests\ImageUpdateRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;

class ImageController extends ApiController
{
    public function index()
    {
        return ImageResource::collection(Image::all());
    }

    public function store(ImageStoreRequest $request)
    {
        $image = Image::create($request->validated());

        return new ImageResource($image);
    }

    public function show(string $id)
    {
        $image = Image::findOrFail($id);

        return new ImageResource($image);
    }

    public function update(ImageUpdateRequest $request, string $id)
    {
        $image = Image::findOrFail($id);
        $image->update($request->validated());

        return new ImageResource($image);
    }

    public function destroy(string $id)
    {
        $image = Image::findOrFail($id);
        $image->delete();

        return response()->noContent();
    }
}
