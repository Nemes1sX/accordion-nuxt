<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\SingleUserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index() : JsonResponse
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

    public function show(User $user) : JsonResponse
    {   
        $tasks = $user->tasks()->select('id')->get();

        return response()->json([
            'user' => SingleUserResource::make($user),
            'tasks' => TaskResource::collection($tasks),
        ], 200);
    }

    public function store(StoreUserRequest $request) : JsonResponse
    {
        User::create($request->validated());

        return response()->json([
            'success' => 'User created successfully'        
        ], 200);
    }

    public function update(UpdateUserRequest $request, User $user) : JsonResponse
    {
        $user->update($request->validated());

        
        return response()->json([
            'success' => 'User updated succesfully'        
        ], 200);
    }

    public function destroy(User $user) : JsonResponse
    {
        $user->delete();

        return response()->json([
            'success' => 'User deleted succesfully'        
        ], 200);
    }
}
