<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Reading;
use Illuminate\Database\Seeder;

class ReadingSeeder extends Seeder
{
    public function run(): void
    {
        $equipment = Equipment::where('code', 'ESP32_ATP_SIM')->firstOrFail();

        // 2 horas de lecturas, una por minuto, parecidas al JSON de ejemplo
        $rows = [];
        $start = now()->subHours(2)->startOfMinute();
        for ($i = 0; $i < 120; $i++) {
            $rows[] = [
                'enterprice_id' => $equipment->enterprice_id,
                'equipment_id' => $equipment->id,
                'temperature' => round(58 + sin($i / 10) * 2, 2),
                'ads_voltage' => 2.869,
                'gpio18' => 0,
                'gpio25' => 0,
                'analog_out' => 0.0,
                'dac_voltage' => 0.00,
                'rssi' => -53,
                'uptime' => ($i + 1) * 60,
                'simulated' => true,
                'notes' => 'Equipo en perfecto estado',
                'recorded_at' => $start->copy()->addMinutes($i),
            ];
        }

        Reading::insert($rows);
        $equipment->update(['last_reading_at' => end($rows)['recorded_at']]);
    }
}
