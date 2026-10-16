<?php

namespace Tests\Feature;

use App\Models\Enterprice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnterpriceUserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_crea_y_actualiza_empresa(): void
    {
        $id = $this->postJson('/api/enterprice', ['name' => 'Acme', 'nit' => '123'])
            ->assertCreated()->json('id');

        $this->postJson('/api/enterprice', ['name' => 'Otra', 'nit' => '123'])
            ->assertUnprocessable();

        $this->patchJson("/api/enterprice/$id", ['name' => 'Acme SA', 'status' => false])
            ->assertOk()->assertJsonPath('name', 'Acme SA');
    }

    public function test_crea_y_actualiza_usuario(): void
    {
        $e = Enterprice::create(['name' => 'Acme']);

        $id = $this->postJson('/api/user', [
            'name' => 'Ana', 'email' => 'ana@example.com',
            'password' => 'password123', 'enterprice_id' => $e->id,
        ])->assertCreated()->assertJsonMissing(['password'])->json('id');

        $this->patchJson("/api/user/$id", ['name' => 'Ana M', 'password' => 'nuevaclave123'])
            ->assertOk();

        $this->assertSame('Ana M', User::find($id)->name);
        $this->postJson('/api/user', ['name' => 'X', 'email' => 'ana@example.com', 'password' => 'password123'])
            ->assertUnprocessable();
    }
}
