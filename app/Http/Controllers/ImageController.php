<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');

        $query = Image::query();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:card,recognition,map',
            'value' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
        ]);

        $file = $request->file('image');

        $path = $file->store('images', 'public');

        $image = Image::create([
            'type' => $request->input('type'),
            'value' => $request->input('value'),
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);

        return response()->json($image, 201);
    }

    public function destroy(Image $image)
    {
        if ($image->path) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return response()->json(['status' => 'ok']);
    }
}
