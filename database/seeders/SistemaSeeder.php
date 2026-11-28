<?php

namespace Database\Seeders;

use App\Models\Sistema;
use Illuminate\Database\Seeder;

class SistemaSeeder extends Seeder
{
    /**
     * Sistemas del vehículo que se usan como categorías del catálogo.
     */
    public const SISTEMAS = [
        'Aire comprimido',
        'Cardán',
        'Dirección',
        'Eléctrico',
        'Filtración',
        'Frenos',
        'Motor',
        'Refrigeración',
        'Ruedas',
        'Suspensión',
        'Transmisión',
    ];

    public function run(): void
    {
        foreach (self::SISTEMAS as $nombre) {
            Sistema::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
