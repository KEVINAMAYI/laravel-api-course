<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * @group Users
 *
 * APIs for managing users
 */
class UserController extends Controller
{

    /**
     * Get Users
     *
     * This endpoint allows you to get all users.
     *
     * @authenticated
     *
     *
     * @response 200 scenario="Get Users"
     * [
     * {
     * "id": 1,
     * "name": "Admin",
     * "email": "admin@admin.com",
     * "products": [
     * {
     * "id": 1,
     * "productName": "Shirt",
     * "productPrice": "$600",
     * "discountedPrice": "$540",
     * "discount": "$60"
     * },
     * {
     * "id": 3,
     * "productName": "Bed",
     * "productPrice": "$6000",
     * "discountedPrice": "$5400",
     * "discount": "$600"
     * }
     * ]
     * },
     * {
     * "id": 3,
     * "name": "User",
     * "email": "user@user.com",
     * "products": []
     * }
     * ]
     *
     */
    public function index()
    {
        try {

            return response()->json(UserResource::collection(User::all()), 200);

        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);

        }
    }


    /**
     * Add User
     *
     * This endpoint allows you to add a user.
     *
     * @authenticated
     *
     * @bodyParam name string required The name of the user. Example: User
     * @bodyParam email string required The email of the user. Example: user@user.com
     * @bodyParam password string required The password of the user. Example: test1234
     *
     *
     * @response 201 scenario="Add a User"
     * {
     * "id": 3,
     * "name": "User",
     * "email": "user@user.com",
     * "products": []
     * }
     *
     */
    public function store(UserStoreRequest $request)
    {
        DB::beginTransaction();
        try {

            $user = User::create($request->validated());

            DB::commit();
            return response()->json(new UserResource($user), 201);

        } catch (Exception $exception) {

            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()], 500);

        }


    }

    /**
     * Get User
     *
     * This endpoint allows you to get a user.
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the user. Example: 3
     *
     *
     * @response 200 scenario="Add a User"
     * {
     * "id": 3,
     * "name": "User",
     * "email": "user@user.com",
     * "products": []
     * }
     *
     */
    public function show(User $user)
    {
        try {

            return response()->json(new UserResource($user), 200);

        } catch (Exception $exception) {

            return response()->json(['error' => $exception->getCode()], 500);

        }
    }


    /**
     * Update User
     *
     * This endpoint allows you to update a user.
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the user. Example: 3
     *
     * @bodyParam name string required The name of the user. Example: UpdatedUser
     * @bodyParam email string required The email of the user. Example: user@updated.com
     * @bodyParam password string required The password of the user. Example: test123456
     *
     * @response 200 scenario="Update a User"
     * {
     * "id": 3,
     * "name": "UpdatedUser",
     * "email": "user@updated.com",
     * "products":[]
     *
     * }
     *
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        DB::beginTransaction();
        try {

            $user->update($request->validated());

            DB::commit();
            return response()->json(new UserResource($user), 200);
        } catch (Exception $exception) {

            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()], 500);

        }

    }


    /**
     * Delete User
     *
     * This endpoint allows you to delete a user.
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the user. Example: 3
     *
     *
     * @response 204 scenario="Delete a User"
     *
     *
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {


            $data = $user->delete();

            DB::commit();
            return response($data, 204);

        } catch (Exception $exception) {

            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()], 500);

        }
    }
}
