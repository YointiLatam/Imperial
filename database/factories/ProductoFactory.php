<?php

namespace Database\Factories;

use App\Models\Marca;
use App\Models\Producto;
use App\Models\Sistema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->randomElement([
                'Filtro de aire', 'Filtro de aceite', 'Amortiguador', 'Disco de freno',
                'Pastillas de freno', 'Kit embrague', 'Bomba de agua', 'Rodamiento',
                'Cruceta', 'Tensor', 'Engranaje', 'Secador de aire', 'Válvula de freno',
            ]),
            'marca_id' => Marca::factory(),
            'sistema_id' => Sistema::factory(),
            'codigo' => fake()->unique()->numerify('##########'),
            'oem' => fake()->optional(0.8)->numerify('#######'),
            'precio' => fake()->randomFloat(2, 20, 3000),
            'stock' => fake()->boolean(25) ? 0 : fake()->numberBetween(1, 50),
            'descripcion' => fake()->sentence(),
            'aplicacion' => fake()->randomElement([
                'Volvo FH', 'Scania P', 'Scania R', 'Mercedes Actros',
                'Mercedes Atego', 'Ejes de remolque', 'Línea pesada',
            ]),
            'imagen_path' => null,
        ];
    }

    public function agotado(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}
