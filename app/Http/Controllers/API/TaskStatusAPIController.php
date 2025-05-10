<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskStatusResource;
use App\Models\TaskStatus;
use App\Traits\HasResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskStatusAPIController extends Controller
{
    use HasResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {

        $task_statuses = TaskStatus::query()->orderByDesc('created_at')->get();
        return $this->successResponseWithResource("Tasks Lists",  TaskStatusResource::collection($task_statuses));
    }


}
