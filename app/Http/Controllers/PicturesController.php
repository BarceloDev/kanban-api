<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Picture;

class PicturesController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->pictures
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $picture = Picture::create([
            'user_id' => $request->user()->id, // precisa estar autenticado
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'access_token' => bin2hex(random_bytes(32)),
        ]);

        return response()->json($picture, 201);
    }

    public function share($token)
    {
        $picture = Picture::where('access_token', $token)->firstOrFail();

        return response()->json($picture);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $picture = Picture::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $picture->update($validated);

        return response()->json($picture);
    }

    public function destroy(Request $request, Picture $picture)
    {
        if ($picture->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $picture->delete();

        return response()->json([
            'message' => 'Picture deleted successfully'
        ]);
    }

    public function show(Request $request, $id)
    {
        return Picture::where('user_id', $request->user()->id)
            ->with('tasks')
            ->findOrFail($id);
    }
}
