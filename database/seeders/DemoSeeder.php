<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\Producto;
use App\Models\Sistema;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Datos de prueba para desarrollo local. No se ejecuta en producción.
 */
class DemoSeeder extends Seeder
{
    private const MARCAS = [
        'WABCO', 'Valeo', 'Dayco', 'Sachs', 'Mann', 'Knorr',
        'SKF', 'FAG', 'Spicer', 'INA', 'Gates', 'Haldex',
    ];

    public function run(): void
    {
        $marcas = collect(self::MARCAS)
            ->map(fn (string $nombre) => Marca::firstOrCreate(['nombre' => $nombre]));

        $sistemas = Sistema::all();

        if (! User::where('email', 'trabajador@imperial.test')->exists()) {
            User::factory()->create(['email' => 'trabajador@imperial.test']);
        }

        Producto::factory()
            ->count(30)
            ->sequence(fn () => [
                'marca_id' => $marcas->random()->id,
                'sistema_id' => $sistemas->random()->id,
            ])
            ->create();
    }
}
