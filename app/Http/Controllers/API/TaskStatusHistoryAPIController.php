<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskStatusHistoryResource;
use App\Models\TaskStatusHistory;
use App\Traits\HasResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskStatusHistoryAPIController extends Controller
{
    use HasResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $taskId): JsonResponse
    {
        $history = TaskStatusHistory::query()->where('task_id', $taskId)
            ->with('oldStatus', 'newStatus')
            ->orderBy('changed_at', 'desc')
            ->paginate(10);

        return $this->successResponseWithResource('Status history fetched successfully',
            TaskStatusHistoryResource::collection($history)->response()->getData());
    }

}
