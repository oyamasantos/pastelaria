<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Client;

class ClientTest extends TestCase
{
    public function test_create_client()
    {
        $email = 'test' . rand(1000, 9999) . '@email.com';
        $client = Client::create([
            'name' => 'Test Client',
            'email' => $email,
            'phone' => '999999999',
            'birth_date' => '1990-01-01',
            'address' => 'Rua Teste',
            'neighborhood' => 'Centro',
            'zip_code' => '12345-678'
        ]);

        $this->assertDatabaseHas('clients', ['email' => $email]);
    }

    public function test_get_clients()
    {
        $response = $this->get('/api/clients');
        $response->assertStatus(200);
    }
}
