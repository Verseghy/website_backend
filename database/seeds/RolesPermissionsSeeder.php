<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        $this->seedPermissions();
        $this->seedRoles();
        $this->seedUsers();
    }

    private function seedPermissions()
    {
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'edit events']);
        Permission::create(['name' => 'edit canteens']);
        Permission::create(['name' => 'edit users']);
    }

    private function seedRoles()
    {
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo('edit posts');

        $role = Role::create(['name' => 'secretary']);
        $role->givePermissionTo(['edit canteens', 'edit events']);

        $role = Role::create(['name' => 'supervisor']);
        $role->givePermissionTo(['edit posts']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
    }

    private function seedUsers()
    {
        User::where('name', 'LIKE', 'test')->first()->assignRole('admin');
    }
}
