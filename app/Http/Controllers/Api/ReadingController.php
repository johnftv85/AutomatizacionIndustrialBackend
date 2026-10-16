<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Reading;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReadingController extends Controller
{
    /** Recibe una lectura enviada por un equipo (autenticado con HTTP Basic). */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'enterprice' => ['required', 'integer'],
            'equipo' => ['required', 'string', 'max:100'],
            'temperatura' => ['nullable', 'numeric', 'between:-9999.99,9999.99'],
            'voltajeADS' => ['nullable', 'numeric', 'between:-999.999,999.999'],
            'gpio18' => ['nullable', 'boolean'],
            'gpio25' => ['nullable', 'boolean'],
            'analogOut' => ['nullable', 'numeric', 'between:-999999.99,999999.99'],
            'voltajeDAC' => ['nullable', 'numeric', 'between:-9999.99,9999.99'],
            'rssi' => ['nullable', 'integer', 'between:-32768,32767'],
            'uptime' => ['nullable', 'integer', 'min:0'],
            'simulado' => ['nullable', 'boolean'],
            'notas' => ['nullable', 'string', 'max:500'],
        ]);

        $equipment = Equipment::where('enterprice_id', $data['enterprice'])
            ->where('code', $data['equipo'])
            ->first();

        if (! $equipment) {
            return response()->json(['message' => 'El equipo no existe en la empresa'], 404);
        }

        $now = now();

        $reading = Reading::create([
            'enterprice_id' => $equipment->enterprice_id,
            'equipment_id' => $equipment->id,
            'temperature' => $data['temperatura'] ?? null,
            'ads_voltage' => $data['voltajeADS'] ?? null,
            'gpio18' => $data['gpio18'] ?? false,
            'gpio25' => $data['gpio25'] ?? false,
            'analog_out' => $data['analogOut'] ?? null,
            'dac_voltage' => $data['voltajeDAC'] ?? null,
            'rssi' => $data['rssi'] ?? null,
            'uptime' => $data['uptime'] ?? null,
            'simulated' => $data['simulado'] ?? false,
            'notes' => $data['notas'] ?? null,
            'recorded_at' => $now,
        ]);

        $equipment->update(['last_reading_at' => $now]);

        return response()->json($reading, 201);
    }
}
