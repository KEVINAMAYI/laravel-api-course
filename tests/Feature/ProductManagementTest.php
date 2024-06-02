<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\AuthTrait;

class ProductManagementTest extends TestCase
{
    use AuthTrait;

    /**
     * A basic feature test example.
     */
    public function test_get_products()
    {
        User::factory()->count(10)->create();

        Product::factory()->count(10)->create();

        $response = $this->loginWithHeaders()->getJson('api/products');

        $response->assertStatus(200)->assertJsonStructure([
            ['id', 'productName', 'productPrice', 'discountedPrice', 'discount']
        ]);

    }

    public function test_get_product()
    {
        $product = Product::first();

        $response = $this->loginWithHeaders()->getJson('api/products/' . $product->id);

        $response->assertStatus(200)->assertJsonStructure(
            ['id', 'productName', 'productPrice', 'discountedPrice', 'discount']
        );


    }

    public function test_create_product()
    {

        $response = $this->loginWithHeaders()->postJson('api/products', [
            'user_id' => User::first()->id,
            'name' => 'testProduct',
            'price' => 200,
            'description' => 'testProduct'
        ]);

        $response->assertStatus(201)->assertJsonStructure(
            ['id', 'productName', 'productPrice', 'discountedPrice', 'discount']
        );

    }

    public function test_update_product()
    {

        $product = Product::first();

        $response = $this->loginWithHeaders()->putJson('api/products/' . $product->id, [
            'name' => 'updatedtestProduct',
            'price' => 400,
            'description' => 'updated test Product'
        ]);

        $response->assertStatus(200)->assertJsonStructure(
            ['id', 'productName', 'productPrice', 'discountedPrice', 'discount']
        );

        $this->assertDatabaseHas('products', [
            'name' => 'updatedtestProduct',
            'price' => 400,
            'description' => 'updated test Product'
        ]);

    }

    public function test_delete_product()
    {

        $product = Product::first();

        $response = $this->loginWithHeaders()->deleteJson('api/products/' . $product->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);

    }

}
