<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Traits\HasResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Task;

use Illuminate\Validation\Rule;

class TaskAPIController extends Controller
{
    use HasResponseTrait;


    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'due_date' => 'required|date|after:now',
        ]);

        $task = Task::create($data);
        return $this->successResponseWithResource("Task creation successful", new TaskResource($task), 201);
    }

    public function show($id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->failedResponse("Invalid Request", ['error' => 'Task not found'], 404);
        }
        return $this->successResponseWithResource("Task returned", new TaskResource($task), 200);
    }

    public function index(): JsonResponse
    {
        $task = Task::paginate(20);
        return response()->json(Task::all());
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $request->validate([
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
        ]);

        $task->status = $request->status;
        $task->save();

        return response()->json($task);
    }

    public function destroy($id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
