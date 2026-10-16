<?php

namespace Tests\Feature;

use App\Models\Enterprice;
use App\Models\Equipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadingApiTest extends TestCase
{
    use RefreshDatabase;

    private function payload(int $enterprice, string $code = 'ESP32_ATP_SIM'): array
    {
        return [
            'enterprice' => $enterprice, 'equipo' => $code, 'temperatura' => 58.0,
            'voltajeADS' => 2.869, 'gpio18' => 0, 'gpio25' => 0, 'analogOut' => 0.0,
            'voltajeDAC' => 0.00, 'rssi' => -53, 'uptime' => 10, 'simulado' => true,
            'notas' => 'Equipo en perfecto estado',
        ];
    }

    private function auth(string $user = '@ATPEcontrol', string $pass = 'atpeControl123'): array
    {
        config(['services.device.username' => '@ATPEcontrol', 'services.device.password' => 'atpeControl123']);

        return ['Authorization' => 'Basic '.base64_encode("$user:$pass")];
    }

    public function test_equipo_guarda_lectura(): void
    {
        $e = Enterprice::create(['name' => 'Acme']);
        $eq = Equipment::create(['enterprice_id' => $e->id, 'code' => 'ESP32_ATP_SIM']);

        $this->postJson('/api/readings', $this->payload($e->id), $this->auth())->assertCreated();

        $this->assertDatabaseHas('readings', [
            'equipment_id' => $eq->id, 'enterprice_id' => $e->id,
            'temperature' => 58.0, 'rssi' => -53, 'simulated' => 1, 'notes' => 'Equipo en perfecto estado',
        ]);
        $this->assertNotNull($eq->fresh()->last_reading_at);
    }

    public function test_rechaza_credenciales_malas_y_equipo_inexistente(): void
    {
        $e = Enterprice::create(['name' => 'Acme']);
        Equipment::create(['enterprice_id' => $e->id, 'code' => 'ESP32_ATP_SIM']);

        $this->auth();
        $this->postJson('/api/readings', $this->payload($e->id))->assertUnauthorized();
        $this->post('/api/readings', $this->payload($e->id))->assertUnauthorized();
        $this->postJson('/api/readings', $this->payload($e->id), $this->auth(pass: 'mala'))->assertUnauthorized();
        $this->postJson('/api/readings', $this->payload($e->id, 'OTRO'), $this->auth())->assertNotFound();
        $this->postJson('/api/readings', ['enterprice' => $e->id], $this->auth())->assertUnprocessable();
    }
}
