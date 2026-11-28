<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Sistema;
use App\Models\User;
use Database\Seeders\SistemaSeeder;
use Database\Seeders\SuperadminSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_sistema_seeder_loads_the_fixed_list(): void
    {
        $this->seed(SistemaSeeder::class);
        $this->seed(SistemaSeeder::class);

        $this->assertSame(count(SistemaSeeder::SISTEMAS), Sistema::count());
        $this->assertTrue(Sistema::where('slug', 'suspension')->exists());
    }

    public function test_superadmin_seeder_uses_env_credentials(): void
    {
        config(['imperial.admin' => ['email' => 'admin@imperial.test', 'password' => 'secreto']]);

        $this->seed(SuperadminSeeder::class);

        $admin = User::where('email', 'admin@imperial.test')->sole();
        $this->assertSame(Role::Superadmin, $admin->role);
        $this->assertTrue($admin->isSuperadmin());
        $this->assertTrue(password_verify('secreto', $admin->password));
    }

    public function test_superadmin_seeder_skips_without_credentials(): void
    {
        config(['imperial.admin' => ['email' => null, 'password' => null]]);

        $this->seed(SuperadminSeeder::class);

        $this->assertSame(0, User::count());
    }

    public function test_producto_slug_includes_codigo_and_does_not_change_on_update(): void
    {
        $producto = Producto::factory()->create(['nombre' => 'Válvula de freno', 'codigo' => '4324070700']);

        $this->assertSame('valvula-de-freno-4324070700', $producto->slug);

        $producto->update(['nombre' => 'Válvula relé']);

        $this->assertSame('valvula-de-freno-4324070700', $producto->fresh()->slug);
    }

    public function test_marca_and_sistema_slugs_follow_the_name(): void
    {
        $marca = Marca::factory()->create(['nombre' => 'Knorr Bremse']);
        $this->assertSame('knorr-bremse', $marca->slug);

        $marca->update(['nombre' => 'Knorr']);
        $this->assertSame('knorr', $marca->slug);
    }

    public function test_en_stock_depends_on_stock(): void
    {
        $this->assertTrue(Producto::factory()->make(['stock' => 3])->en_stock);
        $this->assertFalse(Producto::factory()->agotado()->make()->en_stock);
    }

    public function test_producto_records_who_created_and_updated_it(): void
    {
        $creador = User::factory()->create();
        $editor = User::factory()->create();

        $this->actingAs($creador);
        $producto = Producto::factory()->create();

        $this->actingAs($editor);
        $producto->update(['stock' => 10]);

        $this->assertTrue($producto->creador->is($creador));
        $this->assertTrue($producto->fresh()->editor->is($editor));
    }

    public function test_marca_with_productos_cannot_be_deleted(): void
    {
        $producto = Producto::factory()->create();

        $this->expectException(QueryException::class);

        $producto->marca->delete();
    }

    public function test_sistema_with_productos_cannot_be_deleted(): void
    {
        $producto = Producto::factory()->create();

        $this->expectException(QueryException::class);

        $producto->sistema->delete();
    }

    public function test_productos_and_users_are_soft_deleted(): void
    {
        $producto = Producto::factory()->create();
        $user = User::factory()->create();

        $producto->delete();
        $user->delete();

        $this->assertSoftDeleted($producto);
        $this->assertSoftDeleted($user);
    }
}
