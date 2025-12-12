<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->latest()
            ->paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'min:3', 'max:255', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:5', 'string'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        return (new UserResource($user))
            ->additional([
                'success' => true,
                'message' => 'User created successfully',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return (new UserResource($user))
            ->additional([
                'success' => true,
                'message' => 'User retrieved successfully',
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['sometimes', 'min:3', 'max:255', 'string'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $user->id],
            'password' => ['sometimes', 'min:5', 'string'],
        ];

        if ($request->isMethod('put')) {
            $rules['name'][] = 'required';
            $rules['email'][] = 'required';
            $rules['password'][] = 'required';
        }

        $validated = $request->validate($rules);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return (new UserResource($user->fresh()))
            ->additional([
                'success' => true,
                'message' => 'User updated successfully',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ], Response::HTTP_OK);
    }
}
