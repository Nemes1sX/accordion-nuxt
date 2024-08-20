<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $perPage = 10;
        $users = User::paginate($perPage);

        return response()->json([
            'page' => $users->currentPage(),
            'users' => UserResource::collection($users->items()),
            'totalRecords' => $users->total(),
            'totalPages' => ceil($users->total()/$perPage)
        ], 200);
    }

    public function show(User $user)
    {   
        $perPage = 10;
        $tasks = $user->tasks()->paginate($perPage);

        return response()->json([
            'user' => UserResource::make($user),
            'tasks' => TaskResource::collection($tasks->items()),
            'page' => $tasks->currentPage(),
            'totalRecords' => $tasks->total(),
            'totalPages' => ceil($tasks->total()/$perPage)
        ], 200);
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());

        return response()->json([
            'success' => 'User created successfully'        
        ], 200);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        
        return response()->json([
            'success' => 'User updated succesfully'        
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => 'User deleted succesfully'        
        ], 200);
    }
}
