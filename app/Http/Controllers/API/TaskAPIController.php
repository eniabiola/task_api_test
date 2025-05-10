<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\TaskStatusHistory;
use App\Traits\HasResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Task;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class TaskAPIController extends Controller
{
    use HasResponseTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        $validSortColumns = ['task_status_id', 'due_date', 'created_at'];
        $sortBy = $request->query('sort_by', 'created_at');
        $direction  = $request->query('order', 'desc');

        // Validate inputs
        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'created_at';
        }

        if (!in_array(strtolower($direction), ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $tasks = Task::query()
            ->where('user_id', auth()->id())
            ->orderBy($sortBy, $direction)->paginate(10);
        return $this->successResponseWithResource("Tasks Lists",  TaskResource::collection($tasks)->response()->getData());
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $task = Task::query()
            ->where('user_id', \auth()->id())
            ->find($id);
        if (!$task) {
            return $this->failedResponse("Invalid Request", ['error' => 'Task not found'], 404);
        }
        return $this->successResponseWithResource("Task returned", new TaskResource($task), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_status_id' => 'required|exists:task_statuses,id',
            'due_date' => 'required|date_format:Y-m-d\TH:i|after:now',
        ], [
            'due_date.date_format' => "Date format must be Y-m-d\TH:i such as 01/31/2050 01:01 AM"
        ]);
        $data['user_id'] = Auth::id();
        $data['due_date'] = Carbon::parse(strtotime($request->input('due_date')))->format('Y-m-d H:i:s');

        $task = Task::create($data);
        return $this->successResponseWithResource("Task creation successful", new TaskResource($task), 201);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
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


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $task = Task::query()->where('user_id', auth()->id())->find($id);
        if (!$task) {
            return $this->failedResponse("Invalid request",['error' => 'Task not found'], 404);
        }

        $previous_status = $task->task_status_id;

        $request->validate([
            'task_status_id' => 'required|exists:task_statuses,id',
        ]);

        $task->task_status_id = $request->task_status_id;
        $task->save();

        TaskStatusHistory::query()->create([
           'task_id' => $task->id,
           'old_status_id' => $previous_status,
           'new_status_id' => $request->input('task_status_id'),
           'changed_at'    => now()
        ]);

        return $this->successResponseWithResource("task status updated successfully", new TaskResource($task), 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $task = Task::query()->where('user_id', auth()->id())->find($id);
        if (!$task) {
            return $this->failedResponse("Invalid Request", ['error' => 'Task not found'], 404);
        }
        $task->delete();
        return $this->successResponse('Task deleted successfully', [], 204);
    }
}
