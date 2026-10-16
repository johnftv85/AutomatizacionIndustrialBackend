# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Descripción

API de SensorIQ: backend Laravel 12 (PHP ^8.2) multi-empresa para monitoreo de equipos industriales (ESP32 que reportan temperatura, voltajes, RSSI, etc.), gestión de componentes, mantenimiento e inventario/ERP. Usa Laravel Sanctum. Código, comentarios y seeders están en español (los identificadores en inglés). El `README.md` es el genérico de Laravel.

## Comandos

- `composer setup` — instala dependencias, copia `.env`, genera key, migra, `npm install` y build.
- `composer dev` — levanta servidor, queue listener, `pail` (logs) y Vite en paralelo.
- `composer test` — limpia config y ejecuta `php artisan test` (PHPUnit 11; `tests/Unit` y `tests/Feature`).
- Un solo test: `php artisan test --filter=NombreDelTest` o `php artisan test tests/Feature/ExampleTest.php`.
- Lint/formato: `vendor/bin/pint` (Laravel Pint).
- Base de datos: `php artisan migrate:fresh --seed` recrea todo y ejecuta `DatabaseSeeder`. El `.env.example` usa SQLite por defecto y `QUEUE_CONNECTION=database`.

## Arquitectura

- **Multi-tenant por empresa**: casi todas las tablas llevan `enterprice_id` (nótese la ortografía "enterprice", usada en todo el proyecto: no "corregirla"). El modelo `Enterprice` usa la tabla en **singular** `enterprice` (`$table` explícito) y las FKs apuntan a `constrained('enterprice')`. `User` pertenece a una empresa.
- **Equipos autenticados con Sanctum**: `Equipment` usa `HasApiTokens`; cada dispositivo tiene su propio token (`createToken('device')`) y lo envía como `Authorization: Bearer`. `EnterpriceSeeder` imprime el token una sola vez.
- **Lecturas**: `Reading` (crudas, sin `created_at/updated_at`, usa `recorded_at`; en MySQL/MariaDB la migración crea la tabla **particionada por mes**, en otros motores como SQLite una tabla normal) y `HourlyReading` (agregados por hora, único por `equipment_id + hour`).
- **Equipos y componentes**: `EquipmentType` define una plantilla de componentes (`ComponentTemplate`, jerárquica por `parent_id`); `Equipment::applyTemplate()` copia la plantilla a `Component` conservando la jerarquía. Los componentes tienen vida útil (`lifespan_days/hours`) y `max_temperature`.
- **Mantenimiento**: `Maintenance` con constantes de origen (`manual`, `time`, `reading`) y estado (`proposed`, `approved`, `in_progress`, `done`, `cancelled`); se relaciona con equipo, componente, ítem y proveedor, y guarda `erp_reference`.
- **Catálogo/ERP**: `Item`, `Supplier`, `ItemSupplier` (precios), `InventoryLevel`, `ErpConnection` (una por empresa).
- **Seeders** (`DatabaseSeeder`): `EnterpriceSeeder` (empresas, usuarios `admin1@example.com`/`admin2@example.com` con contraseña `password`, tipo, equipo y token) → `CatalogSeeder` → `ReadingSeeder`. Solo para desarrollo.
- **Rutas**: `routes/api.php` está en estado inicial (solo rutas de ejemplo `/user/{id}` con closures; la ruta Sanctum `/user` está comentada). Aún no hay controladores más allá de `Controller` base, ni factories ni tests propios; no hay middleware personalizado en `bootstrap/app.php`.
