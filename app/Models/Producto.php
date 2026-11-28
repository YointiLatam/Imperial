<?php

namespace App\Models;

use Database\Factories\ProductoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

#[Fillable([
    'nombre', 'marca_id', 'sistema_id', 'codigo', 'oem', 'precio',
    'stock', 'descripcion', 'aplicacion', 'imagen_path',
])]
class Producto extends Model
{
    /** @use HasFactory<ProductoFactory> */
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        // El slug se fija al crear y no cambia, para no romper enlaces compartidos.
        // Incluye el código (único) para que dos productos con el mismo nombre no choquen.
        static::creating(function (Producto $producto) {
            if (blank($producto->slug)) {
                $producto->slug = Str::slug("{$producto->nombre} {$producto->codigo}");
            }

            $producto->created_by ??= Auth::id();
            $producto->updated_by ??= Auth::id();
        });

        static::updating(function (Producto $producto) {
            if (Auth::check()) {
                $producto->updated_by = Auth::id();
            }
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function enStock(): Attribute
    {
        return Attribute::get(fn () => $this->stock > 0);
    }

    /**
     * @return BelongsTo<Marca, $this>
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    /**
     * @return BelongsTo<Sistema, $this>
     */
    public function sistema(): BelongsTo
    {
        return $this->belongsTo(Sistema::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }
}
