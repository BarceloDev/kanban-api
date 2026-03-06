<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Picture;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request, $pictureId)
    {
        $picture = Picture::where('user_id', $request->user()->id)
            ->with('tasks')
            ->findOrFail($pictureId);

        return response()->json($picture->tasks);
    }

    public function store(Request $request, $pictureId)
    {
        $picture = Picture::where('user_id', $request->user()->id)
            ->findOrFail($pictureId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,doing,done',
        ]);

        $task = $picture->tasks()->create($validated);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        if ($task->picture->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,doing,done',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy(Request $request, Task $task)
    {
        if ($task->picture->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}