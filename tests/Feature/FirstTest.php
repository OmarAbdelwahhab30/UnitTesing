<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FirstTest extends TestCase
{

    private User $user;
    private User $admin ;
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->addUser();

        $this->admin = $this->addUser(admin: true);
    }


    public function test_unauthenticated_user_cannot_access_products()
    {
        $response = $this->getJson("api/allProducts");

        $response->assertStatus(401);

    }



    public function test_only_authenticated_users_can_show_products()
    {

        $response = $this->actingAs($this->admin)->getJson("api/allProducts");

        $response->assertStatus(200);

    }

    public function test_only_admins_can_add_product()
    {
        $response = $this->actingAs($this->admin)->postJson("api/addProduct",[
            'name'  => "First Product",

            'price' => 123,
        ]);

        $response->assertStatus(200);

    }

    public function test_added_product_has_saved_in_database()
    {
        $product = Product::factory()->make()->attributesToArray();

        $response = $this->actingAs($this->admin)->postJson("api/addProduct" , $product);

        $response->assertStatus(200);

        $this->assertDatabaseHas("products",$product);
    }


    public function test_updated_product_may_have_errors()
    {
        $product = $this->createProduct();

        $response = $this->actingAs($this->admin)->putJson("api/updateProduct/".$product->id,[
           'name'   =>  "",
           'price'  =>  '',
        ]);

        $response->assertInvalid(['name', 'price']);
    }

    public function test_product_deleted_successfully()
    {
        $product = $this->createProduct();

        $response = $this->actingAs($this->admin)->deleteJson("api/deleteProduct/".$product->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing("products",$product->toArray());

    }

    public function test_product_is_within_products()
    {
        $product = $this->createProduct();

        $response = $this->actingAs($this->user)->getJson("api/allProducts");

        $response->assertStatus(200);

        $response->assertJsonFragment([$product->toArray()]);

    }

    private function addUser($admin = false)
    {
        return User::factory()->create([

            'is_admin'  => $admin,

        ]);

    }

    private function createProduct(){

        return Product::factory()->create();

    }

}
