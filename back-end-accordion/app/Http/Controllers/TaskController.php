<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\SingleTaskResource;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    public function store(User $user, StoreTaskRequest $request)
    {
        $user->tasks()->create($request->validated());

        return response()->json([
           'message' => 'User task created successfully'     
        ], 200);
    }

    public function show(User $user, Task $task)
    {
        return response()->json([
            'task' => SingleTaskResource::make($task)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, Task $task, UpdateTaskRequest $request)
    {
        $task->update($request->validated());

        return response()->json([
            'message' => 'User task updated successfully'     
         ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Task $task)
    {
        $task->delete();

        return response()->json([
           'message' => 'User task deleted successfully'     
        ], 200);
    }
}
