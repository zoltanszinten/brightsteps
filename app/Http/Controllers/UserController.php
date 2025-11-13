<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user(Request $request)
    {
        $user = $request->user();

        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()
            ->pluck('name')
            ->values();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type,
            ],
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function children()
    {
        $children = User::where('type', 'token')->get();

        return response()->json([
            'children' => $children,
        ]);
    }

    public function childCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'settings' => 'nullable|array',
            'settings.width' => 'nullable|integer',
            'settings.font_size' => 'nullable|integer',
            'settings.letter_spacing' => 'nullable|integer',
            'settings.line_height' => 'nullable|integer',
            'settings.color_palette_id' => 'nullable|integer|exists:color_palettes,id',
            'settings.show_card_game' => 'nullable|boolean',
            'settings.show_recognition_game' => 'nullable|boolean',
            'settings.show_map_game' => 'nullable|boolean',
        ]);

        $emailIdent = bin2hex(random_bytes(16));

        $child = User::create([
            'name' => $request->name,
            'email' => "$emailIdent@brightsteps.hu",
            'type' => 'token',
            'password' => bcrypt(bin2hex(random_bytes(32))),
        ]);

        $settings = $request->input('settings', []);

        $child->setting()->create([
            'width' => $settings['width'] ?? 1280,
            'font_size' => $settings['font_size'] ?? 18,
            'letter_spacing' => $settings['letter_spacing'] ?? 4,
            'line_height' => $settings['line_height'] ?? 160,
            'color_palette_id' => $settings['color_palette_id'] ?? 1,
            'show_card_game' => $settings['show_card_game'] ?? true,
            'show_recognition_game' => $settings['show_recognition_game'] ?? true,
            'show_map_game' => $settings['show_map_game'] ?? true,
        ]);

        return response()->json([
            'child' => $child->load('setting'),
        ], 201);
    }

    public function childDetail($id)
    {
        $child = User::where('type', 'token')->findOrFail($id);

        return response()->json([
            'child' => $child->load('setting'),
        ]);
    }

    public function childUpdate(Request $request, $id)
    {
        $child = User::where('type', 'token')->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'settings' => 'nullable|array',
            'settings.width' => 'nullable|integer',
            'settings.font_size' => 'nullable|integer',
            'settings.letter_spacing' => 'nullable|integer',
            'settings.line_height' => 'nullable|integer',
            'settings.color_palette_id' => 'nullable|integer|exists:color_palettes,id',
            'settings.show_card_game' => 'nullable|boolean',
            'settings.show_recognition_game' => 'nullable|boolean',
            'settings.show_map_game' => 'nullable|boolean',
        ]);

        if ($request->has('name')) {
            $child->name = $request->name;
            $child->save();
        }

        $child->setting->update($request->input('settings'));

        return response()->json([
            'child' => $child->load('setting'),
        ]);
    }

    public function childDelete($id)
    {
        $child = User::where('type', 'token')->findOrFail($id);

        $child->setting()->delete();
        $child->delete();

        return response()->json([
            'message' => 'Child user and settings are deleted successfully.',
        ]);
    }
}
