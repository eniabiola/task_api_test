<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Traits\HasResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Task;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskAPIController extends Controller
{
    use HasResponseTrait;

    public function index(): JsonResponse
    {
        $tasks = Task::query()->orderByDesc('created_at')->paginate(10);
        return $this->successResponseWithResource("Tasks Lists",  TaskResource::collection($tasks)->response()->getData());
    }

    public function show($id): JsonResponse
    {
        $task = Task::query()->find($id);
        if (!$task) {
            return $this->failedResponse("Invalid Request", ['error' => 'Task not found'], 404);
        }
        return $this->successResponseWithResource("Task returned", new TaskResource($task), 200);
    }
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_status_id' => 'required|exists:task_statuses,id',
            'due_date' => 'required|date|after:now',
        ]);
        $data['user_id'] = Auth::id();

        $task = Task::create($data);
        return $this->successResponseWithResource("Task creation successful", new TaskResource($task), 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $task = Task::query()->find($id);

        if (!$task) {
            return $this->failedResponse("Invalid Request", ['error' => 'Task not found'], 404);
        }

        $data = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'sometimes|date|after:now',
        ]);

        $task->update($data);

        return $this->successResponseWithResource("Task successfully updated", new TaskResource($task), 204);

    }


    public function updateStatus(Request $request, $id): JsonResponse
    {
        $task = Task::query()->find($id);
        if (!$task) {
            return $this->failedResponse("Invalid request",['error' => 'Task not found'], 404);
        }

        $request->validate([
            'task_status_id' => ['required|exists:task_statuses,id'],
        ]);

        $task->track_status_id = $request->track_status_id;
        $task->save();

        return $this->successResponseWithResource("task status updated successfully", new TaskResource($task), 204);
    }

    public function destroy($id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->failedResponse("Invalid Request", ['error' => 'Task not found'], 404);
        }
        $task->delete();
        return $this->successResponse('Task deleted successfully');
    }
}
