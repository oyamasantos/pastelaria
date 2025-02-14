<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Client;
use App\Models\Product;

class OrderTest extends TestCase
{
    public function test_create_order()
    {
        $client = Client::factory()->create();
        $product = Product::factory()->create();
        
        $order = Order::create([
            'client_id' => 1
        ]);

        $order->products()->attach([$product->id]);

        $this->assertDatabaseHas('orders', ['client_id' => $client->id]);
    }

    public function test_get_orders()
    {
        $response = $this->get('/api/orders');
        $response->assertStatus(200);
    }
}
