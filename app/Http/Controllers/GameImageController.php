<?php

namespace App\Http\Controllers;

use App\Models\GameImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GameImageController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');

        $query = GameImage::query();

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

        $path = $file->store('game-images', 'public');

        $image = GameImage::create([
            'type' => $request->input('type'),
            'value' => $request->input('value'),
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);

        return response()->json($image, 201);
    }

    public function destroy(GameImage $gameImage)
    {
        if ($gameImage->path) {
            Storage::disk('public')->delete($gameImage->path);
        }

        $gameImage->delete();

        return response()->json(['status' => 'ok']);
    }
}
