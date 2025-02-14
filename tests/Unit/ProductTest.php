<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    public function test_create_product()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'photo' => 'test.jpg'
        ]);

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_get_products()
    {
        $response = $this->get('/api/products');
        $response->assertStatus(200);
    }
}
