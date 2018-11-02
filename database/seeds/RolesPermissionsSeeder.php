<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
        Permission::create(['name' => 'edit newsletter']);
    }

    private function seedRoles()
    {
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo('edit posts');

        $role = Role::create(['name' => 'secretary']);
        $role->givePermissionTo(['edit canteens', 'edit events', 'edit newsletter']);

        $role = Role::create(['name' => 'supervisor']);
        $role->givePermissionTo(['edit posts']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
    }

    private function seedUsers()
    {
        $user = config('backpack.base.user_model_fqn');
        $u = $user::where('name', 'LIKE', 'test')->first();
        $u->assignRole('admin');
        $u->save();
    }
}