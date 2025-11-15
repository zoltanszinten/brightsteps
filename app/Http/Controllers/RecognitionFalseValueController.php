<?php

namespace App\Http\Controllers;

use App\Models\RecognitionFalseValue;
use Illuminate\Http\Request;

class RecognitionFalseValueController extends Controller
{
    public function index()
    {
        return RecognitionFalseValue::orderBy('value')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $item = RecognitionFalseValue::create([
            'value' => $request->input('value'),
        ]);

        return response()->json($item, 201);
    }

    public function destroy(RecognitionFalseValue $recognitionFalseValue)
    {
        $recognitionFalseValue->delete();

        return response()->json(['status' => 'ok']);
    }
}
