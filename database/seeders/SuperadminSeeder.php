<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperadminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('imperial.admin.email');
        $password = config('imperial.admin.password');

        if (blank($email) || blank($password)) {
            $this->command?->warn('ADMIN_EMAIL / ADMIN_PASSWORD no están en el .env: no se creó el superadmin.');

            return;
        }

        // firstOrCreate: volver a ejecutar el seeder no cambia la contraseña ya existente.
        User::withTrashed()->firstOrCreate(
            ['email' => $email],
            [
                'nombres' => 'Super',
                'apellidos' => 'Administrador',
                'dni' => '00000000',
                'telefono' => '000000000',
                'role' => Role::Superadmin,
                'password' => $password,
            ],
        );
    }
}
