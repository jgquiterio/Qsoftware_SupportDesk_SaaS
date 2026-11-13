<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,agent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $request->user()->company_id,
            'role' => $request->role,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
        ], 201);
    }

    public function index(Request $request)
    {
        $users = User::where('company_id', $request->user()->company_id)
	    ->select('id', 'name', 'email', 'role', 'is_active', 'created_at')
	    ->get();

	return response()->json([
	    'users' => $users,
	], 200);
    }

    public function show(Request $request, int $id)
    {
        $user = User::where('company_id', $request->user()->company_id)
            ->select('id', 'name', 'email', 'role', 'is_active', 'created_at')
            ->findOrFail($id);

        return response()->json([
            'user' => $user,
        ], 200);
    }

    public function update(Request $request, int $id)
    {
        $user = User::where('company_id', $request->user()->company_id)
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|required|in:admin,agent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only(['name', 'email', 'role']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user,
        ], 200);
    }

    public function toggleStatus(Request $request, int $id)
    {
        $user = User::where('company_id', $request->user()->company_id)
            ->findOrFail($id);

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'message' => 'User status updated successfully.',
            'user' => $user,
        ], 200);
    }

}
