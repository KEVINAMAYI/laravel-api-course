<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @group Authentication
 *
 * APIs for managing authentication
 */
class AuthController extends Controller
{

    /**
     * Register User
     *
     * This Api is used to register a user
     *
     * @bodyParam name string required The name of the user. Example: Admin
     * @bodyParam email string required The email of the user. Example: admin@admin.com
     * @bodyParam password string required The password of the user. Example: test1234
     * @bodyParam confirm_password string required The password of the user. Example: test1234
     *
     * @response 201 scenario="Registering a user" {
     *           "message": "User was successfully registered","token":
     *           "1|w21Al97p6mBb9bbyblCEUqgEzXaFzAdbwAEGoYvg1c454909"
     *        }
     *
     */
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|max:255',
            'confirm_password' => 'required|string|max:255|same:password'
        ]);

        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        if ($user->save()) {

            $tokenResult = $user->createToken('Api Token');

            $token = $tokenResult->plainTextToken;

            return response()->json([
                'message' => 'User was successfully registered',
                'token' => $token
            ], 201);

        }

        return response()->json([
            'error' => 'Please Provide Proper Credentials',
        ], 500);

    }


    /**
     * Login User
     *
     *This Api is used to login a user
     *
     * @bodyParam email string required The email of the user. Example: admin@admin.com
     * @bodyParam password string required The password of the user. Example: test1234
     *
     * @response 200 scenario="Login a user"
     *    {
     *       "message": "Login was successful",
     *       "user": { "id": 1,
     *                 "name": "Admin",
     *                 "email": "admin@admin.com",
     *                 "products": []
     *               },
     *       "token": "2|VjVtD7L4S50TKzSycnSq1YFj6NQWW6QbNPZ1dssz85c6ee28"
     *   }
     *
     */
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|max:255',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {

            return response()->json([
                'error' => 'User not Authenticated',
            ], 401);

        }

        $user = User::where('email', $request->email)->first();

        $tokenResult = $user->createToken('Api Token');

        $token = $tokenResult->plainTextToken;

        return response()->json([
            'message' => 'Login was successful',
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);

    }


    /**
     * Logout User
     *
     *This Api is used to logout a user
     *
     * @authenticated
     *
     * @response 200 scenario="Logout User"
     * { "message":"User logged out successfully" }
     *
     *
     */
    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'User logged in and out successfully'
        ], 200);
    }
}
