<?php

namespace App\Http\Controllers;

use App\Models\FalseValue;
use Illuminate\Http\Request;

class FalseValueController extends Controller
{
    public function index()
    {
        return FalseValue::orderBy('value')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $item = FalseValue::create([
            'value' => $request->input('value'),
        ]);

        return response()->json($item, 201);
    }

    public function destroy(FalseValue $recognitionFalseValue)
    {
        $recognitionFalseValue->delete();

        return response()->json(['status' => 'ok']);
    }
}
