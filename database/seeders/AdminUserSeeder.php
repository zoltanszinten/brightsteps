<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $email = env('SEED_ADMIN_EMAIL', 'admin@admin.com');
        $password = env('SEED_ADMIN_PASSWORD', 'Asdfasdf123');

        $user = User::firstOrNew(['email' => $email]);
        $user->name = 'Administrator';
        $user->password = Hash::make($password);
        $user->type = 'default';

        $user->save();

        $user->assignRole($adminRole);
    }
}
