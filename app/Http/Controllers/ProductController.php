<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * @group Products
 *
 * APIs for managing products
 */
class ProductController extends Controller
{

    /**
     * Get Products
     *
     * This endpoint allows you to get all products.
     *
     * @authenticated
     *
     * @response 200 scenario="Get All Products"
     * [
     *  {
     * "id": 1,
     * "productName": "Shirt",
     * "productPrice": "$600",
     * "discountedPrice": "$540",
     * "discount": "$60"
     * },
     * {
     * "id": 2,
     * "productName": "Bag",
     * "productPrice": "$400",
     * "discountedPrice": "$360",
     * "discount": "$40"
     *   }
     * ]
     *
     *
     */
    public function index()
    {
        try {

            return response()->json(ProductResource::collection(Product::all()), 200);

        } catch (Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    /**
     * Add Product
     *
     * This endpoint allows you to add a product.
     *
     * @authenticated
     *
     * @bodyParam user_id string required The user_id for the User of product. Example: 3
     * @bodyParam name string required The name of the product. Example: Bag
     * @bodyParam price integer required The price of the product. Example: 200
     * @bodyParam description string required The description of the user. Example: Blue Bag
     *
     * @response 201 scenario="Add a Product"
     *  {
     *   "id": 1,
     *   "productName": "Bag",
     *   "productPrice": "$400",
     *   "discountedPrice": "$360",
     *   "discount": "$40"
     *  }
     *
     */
    public function store(ProductStoreRequest $request)
    {
        DB::beginTransaction();
        try {

            $product = Product::create($request->validated());

            DB::commit();
            return response()->json(new ProductResource($product), 201);

        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()], 500);
        }

    }


    /**
     * Get Product
     *
     * This endpoint allows you to get a product.
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the product. Example: 2
     *
     * @response 200 scenario="Get a Product"
     * {
     * "id": 2,
     * "productName": "Bag",
     * "productPrice": "$400",
     * "discountedPrice": "$360",
     * "discount": "$40"
     * }
     *
     */
    public function show(Product $product)
    {
        try {

            return response()->json(new ProductResource($product), 200);

        } catch (Exception $exception) {

            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    /**
     * Update Product
     *
     * This endpoint allows you to update a product.
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the product. Example: 2
     *
     * @bodyParam user_id string The user_id for the User of product. Example: 3
     * @bodyParam name string required The name of the product. Example: Shirt
     * @bodyParam price integer required The price of the product. Example: 600
     * @bodyParam description string required The description of the user. Example: White
     *
     *
     * @response 200 scenario="Update a Product"
     *  {
     *   "id": 1,
     *   "productName": "Shirt",
     *   "productPrice": "$600",
     *   "discountedPrice": "$540",
     *   "discount": "$60"
     *  }
     *
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {

            $product->update($request->validated());

            DB::commit();
            return response()->json(new ProductResource($product), 200);

        } catch (Exception $exception) {

            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()], 500);
        }

    }


    /**
     * Delete Product
     *
     * This endpoint allows you to delete a product.
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the product. Example: 2
     *
     * @response 204 scenario="Delete a Product"
     *
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {

            $data = $product->delete();

            DB::commit();
            return response()->json($data, 204);

        } catch (Exception $exception) {

            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
