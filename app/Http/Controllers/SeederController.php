<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;

class SeederController extends Controller
{
    /**
     * @OA\Post(
     *     path="/seeder/create-role",
     *     tags={"Seeder"},
     *     summary="Create Admin Role (No Token Required)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Admin"),
     *             @OA\Property(property="description", type="string", example="Full Access")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Role created successfully"),
     *     @OA\Response(response=400, description="Admin role already exists")
     * )
     */
    public function createAdminRole(Request $request)
    {
        // Kiểm tra nếu Role Admin đã tồn tại
        if (Role::where('name', 'Admin')->exists()) {
            return response()->json(['message' => 'Admin role already exists'], 400);
        }

        // Tạo role Admin
        $role = Role::create([
            'name' => 'Admin',
            'description' => 'Full Access'
        ]);

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/seeder/register",
     *     tags={"Seeder"},
     *     summary="Register User as Admin (No Token Required)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "user_name", "email", "password"},
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="user_name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered successfully"),
     *     @OA\Response(response=400, description="Validation error")
     *     
     * )
     */
    public function registerUser(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users,user_name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:32'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 400);
        }
        // Kiểm tra Role Admin tồn tại chưa
        $role = Role::where('name', 'Admin')->first();
        if (!$role) {
            return response()->json(['message' => 'Admin role does not exist. Please run /api/seeder/create-role first.'], 400);
        }

        // Tạo user với Role Admin
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }
}
