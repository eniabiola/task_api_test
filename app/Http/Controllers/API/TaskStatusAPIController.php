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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
