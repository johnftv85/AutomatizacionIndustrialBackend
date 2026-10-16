<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EnterpriceSeeder::class, // empresas, usuarios, tipos de equipo, equipos y token
            CatalogSeeder::class,   // ítems, proveedores, precios, inventario, plantilla
            ReadingSeeder::class,   // lecturas de ejemplo
        ]);
    }
}
