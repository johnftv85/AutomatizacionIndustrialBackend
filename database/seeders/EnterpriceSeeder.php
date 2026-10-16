<?php

namespace Database\Seeders;

use App\Models\Enterprice;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Database\Seeder;

/** Solo para desarrollo: los usuarios de ejemplo usan la contraseña "password". */
class EnterpriceSeeder extends Seeder
{
    public function run(): void
    {
        // El id de la primera empresa (1) corresponde al "enterprice": 1 del JSON de los equipos
        $enterprice1 = Enterprice::firstOrCreate(['nit' => '890911327-1'], ['name' => 'Abracol']);
        $enterprice2 = Enterprice::firstOrCreate(['nit' => '890932134-7'], ['name' => 'Calderas JCT S.A.S.']);

        User::firstOrCreate(['email' => 'admin1@abracol.com'], [
            'enterprice_id' => $enterprice1->id,
            'name' => 'Admin Enterprice 1',
            'password' => 'password',
        ]);
        User::firstOrCreate(['email' => 'admin2@calderasjtc.com'], [
            'enterprice_id' => $enterprice2->id,
            'name' => 'Admin Enterprice 2',
            'password' => 'password',
        ]);

        $type = EquipmentType::create([
            'enterprice_id' => $enterprice1->id,
            'name' => 'Pastry oven',
            'description' => 'Horno con control de temperatura por ESP32',
        ]);

        $equipment = Equipment::create([
            'enterprice_id' => $enterprice1->id,
            'equipment_type_id' => $type->id,
            'code' => 'ESP32_ATP_SIM',     // campo "equipo" del JSON
            'name' => 'ESP32 ATP (simulated)',
        ]);

        // Token del equipo (Sanctum): se muestra una sola vez. El dispositivo lo
        // envía en la cabecera "Authorization: Bearer <token>".
        $token = $equipment->createToken('device')->plainTextToken;
        $this->command?->warn("Token for {$equipment->code}: {$token}");
    }
}
