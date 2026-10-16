<?php

use Carbon\CarbonImmutable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // En MySQL/MariaDB se crea particionada por mes. En otros motores
        // (por ejemplo SQLite en pruebas) se crea una tabla normal.
        if (in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            $this->createPartitioned();
            return;
        }

        Schema::create('readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enterprice_id');
            $table->unsignedBigInteger('equipment_id');
            $table->decimal('temperature', 6, 2)->nullable();
            $table->decimal('ads_voltage', 6, 3)->nullable();
            $table->boolean('gpio18')->default(false);
            $table->boolean('gpio25')->default(false);
            $table->decimal('analog_out', 8, 2)->nullable();
            $table->decimal('dac_voltage', 6, 2)->nullable();
            $table->smallInteger('rssi')->nullable();
            $table->unsignedBigInteger('uptime')->nullable();
            $table->boolean('simulated')->default(false);
            $table->string('notes', 500)->nullable();
            $table->dateTime('recorded_at');

            $table->index(['equipment_id', 'recorded_at']);
            $table->index(['enterprice_id', 'recorded_at']);
        });
    }

    private function createPartitioned(): void
    {
        // Mes actual + 3 siguientes; lo posterior cae en pmax hasta que el
        // comando de mantenimiento cree nuevas particiones.
        $start = CarbonImmutable::now()->startOfMonth();
        $partitions = [];
        for ($i = 0; $i < 4; $i++) {
            $month = $start->addMonths($i);
            $limit = $month->addMonth()->format('Y-m-d');
            $partitions[] = sprintf("PARTITION p%s VALUES LESS THAN ('%s')", $month->format('Ym'), $limit);
        }
        $partitions[] = 'PARTITION pmax VALUES LESS THAN (MAXVALUE)';
        $partitions = implode(",\n    ", $partitions);

        // MySQL no admite llaves foráneas en tablas particionadas, y la llave
        // primaria debe incluir la columna de partición (recorded_at).
        DB::statement("
            CREATE TABLE readings (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                enterprice_id BIGINT UNSIGNED NOT NULL,
                equipment_id BIGINT UNSIGNED NOT NULL,
                temperature DECIMAL(6,2) NULL,
                ads_voltage DECIMAL(6,3) NULL,
                gpio18 TINYINT(1) NOT NULL DEFAULT 0,
                gpio25 TINYINT(1) NOT NULL DEFAULT 0,
                analog_out DECIMAL(8,2) NULL,
                dac_voltage DECIMAL(6,2) NULL,
                rssi SMALLINT NULL,
                uptime BIGINT UNSIGNED NULL,
                simulated TINYINT(1) NOT NULL DEFAULT 0,
                notes VARCHAR(500) NULL,
                recorded_at DATETIME NOT NULL,
                PRIMARY KEY (id, recorded_at),
                KEY readings_equipment_time (equipment_id, recorded_at),
                KEY readings_enterprice_time (enterprice_id, recorded_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            PARTITION BY RANGE COLUMNS (recorded_at) (
                $partitions
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('readings');
    }
};
