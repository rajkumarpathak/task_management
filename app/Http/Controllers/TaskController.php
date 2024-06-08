<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskCompleted;
use App\Notifications\DeadlineApproaching;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    public function index()
    {
        return Task::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'deadline' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task = Task::create($request->all());
        // Trigger TaskAssigned notification
        Notification::route('mail', 'hello@example.com')->notify(new TaskAssigned($task));
        return response()->json($task);
    }

    public function show($id)
    {
        $task = Task::find($id);
        if (is_null($task)) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (is_null($task)) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'deadline' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task->update($request->all());

         // Trigger TaskCompleted or DeadlineApproaching notification based on status
        if ($request->status === 'completed') {
            Notification::route('mail', 'hello@example.com')->notify(new TaskCompleted($task));
        } elseif ($request->deadline && strtotime($request->deadline) < strtotime('+1 day')) {
            Notification::route('mail', 'hello@example.com')->notify(new DeadlineApproaching($task));
        }

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if (is_null($task)) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        $task->delete();
        return response()->json(null, 204);
    }
}

