<?php

namespace App\Models;

use Database\Factories\SistemaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Parte del vehículo a la que pertenece un repuesto (Frenos, Suspensión, Motor...).
 * Es la categoría del catálogo.
 */
#[Fillable(['nombre'])]
class Sistema extends Model
{
    /** @use HasFactory<SistemaFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Sistema $sistema) {
            if (blank($sistema->slug) || $sistema->isDirty('nombre')) {
                $sistema->slug = Str::slug($sistema->nombre);
            }
        });
    }

    /**
     * @return HasMany<Producto, $this>
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
