<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index(User $child)
    {
        return response()->json($child->statistics);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|max:50',
            'points' => 'required|integer|min:0',
            'time' => 'required|integer|min:0',
        ]);

        $stat = Statistic::create([
            'user_id' => $request->user()->id,
            'type' => $data['type'],
            'points' => $data['points'],
            'time' => $data['time'],
        ]);

        return response()->json($stat, 201);
    }
}
