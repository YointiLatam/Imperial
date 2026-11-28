<?php

namespace App\Models;

use Database\Factories\MarcaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['nombre', 'logo_path'])]
class Marca extends Model
{
    /** @use HasFactory<MarcaFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Marca $marca) {
            if (blank($marca->slug) || $marca->isDirty('nombre')) {
                $marca->slug = Str::slug($marca->nombre);
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
