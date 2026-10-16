<?php

namespace Database\Seeders;

use App\Models\Enterprice;
use App\Models\ComponentTemplate;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\InventoryLevel;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $enterprice = Enterprice::firstOrFail();   // Enterprice 1
        $sync = now();

        $heater = Item::create([
            'enterprice_id' => $enterprice->id, 'erp_code' => 'RES-001',
            'description' => 'Heating element 2000W', 'unit' => 'UND', 'synced_at' => $sync,
        ]);
        $sensor = Item::create([
            'enterprice_id' => $enterprice->id, 'erp_code' => 'SEN-010',
            'description' => 'Type K temperature sensor', 'unit' => 'UND', 'synced_at' => $sync,
        ]);
        $fan = Item::create([
            'enterprice_id' => $enterprice->id, 'erp_code' => 'VEN-020',
            'description' => 'Convection fan', 'unit' => 'UND', 'synced_at' => $sync,
        ]);

        $supplierA = Supplier::create([
            'enterprice_id' => $enterprice->id, 'erp_code' => 'P-100',
            'name' => 'Repuestos Industriales S.A.S.', 'synced_at' => $sync,
        ]);
        $supplierB = Supplier::create([
            'enterprice_id' => $enterprice->id, 'erp_code' => 'P-200',
            'name' => 'ElectroPartes Ltda.', 'synced_at' => $sync,
        ]);

        // Un mismo ítem con varios proveedores, precios y plazos distintos
        $heater->suppliers()->attach($supplierA->id, [
            'price' => 185000, 'lead_time_days' => 5, 'preferred' => true, 'synced_at' => $sync,
        ]);
        $heater->suppliers()->attach($supplierB->id, [
            'price' => 172000, 'lead_time_days' => 12, 'preferred' => false, 'synced_at' => $sync,
        ]);
        $sensor->suppliers()->attach($supplierB->id, [
            'price' => 48000, 'lead_time_days' => 3, 'preferred' => true, 'synced_at' => $sync,
        ]);
        $fan->suppliers()->attach($supplierA->id, [
            'price' => 320000, 'lead_time_days' => 7, 'preferred' => true, 'synced_at' => $sync,
        ]);

        foreach ([[$heater, 4], [$sensor, 0], [$fan, 1]] as [$item, $quantity]) {
            InventoryLevel::create([
                'item_id' => $item->id, 'warehouse' => 'MAIN',
                'quantity' => $quantity, 'synced_at' => $sync,
            ]);
        }

        // Plantilla de componentes del tipo "Pastry oven"
        $type = EquipmentType::where('enterprice_id', $enterprice->id)->firstOrFail();

        $chamber = ComponentTemplate::create([
            'equipment_type_id' => $type->id, 'name' => 'Baking chamber',
        ]);
        ComponentTemplate::create([
            'equipment_type_id' => $type->id, 'parent_id' => $chamber->id, 'item_id' => $heater->id,
            'name' => 'Heating element', 'lifespan_days' => 365, 'max_temperature' => 250,
        ]);
        ComponentTemplate::create([
            'equipment_type_id' => $type->id, 'parent_id' => $chamber->id, 'item_id' => $sensor->id,
            'name' => 'Temperature sensor', 'lifespan_days' => 540,
        ]);
        ComponentTemplate::create([
            'equipment_type_id' => $type->id, 'parent_id' => $chamber->id, 'item_id' => $fan->id,
            'name' => 'Convection fan', 'lifespan_hours' => 8000,
        ]);

        // Los equipos heredan los componentes de su tipo
        Equipment::where('enterprice_id', $enterprice->id)->get()->each->applyTemplate();
    }
}
