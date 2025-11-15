<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::orderBy('created_at', 'asc')->paginate(10);

        return Inertia::render('positions/index', [
            'positions' => $positions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pos_code' => 'required|string|max:10',
            'pos_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Position::create([
            'pos_code' => $request->pos_code,
            'pos_name' => $request->pos_name,
            'description' => $request->description,
        ]);

        return redirect()->route('positions.index')->with('success', 'Position created successfully!');
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'pos_code' => 'required|string|max:10',
            'pos_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $position->update([
            'pos_code' => $request->pos_code,
            'pos_name' => $request->pos_name,
            'description' => $request->description,
        ]);

        return redirect()->route('positions.index')->with('success', 'Position updated successfully!');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Position deleted successfully!');
    }
}
