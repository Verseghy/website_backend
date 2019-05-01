<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddPermissions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');
        $this->makePermissions();
        $this->makeRoles();
        $this->makeUsers();
    }

    private function createPermission(string $name): void
    {
        if (!Permission::where('name', '=', $name)->exists()) {
            Permission::create(['name' => $name]);
        }
    }

    public function makePermissions(): void
    {
        $this->createPermission('edit posts');
        $this->createPermission('edit events');
        $this->createPermission('edit canteens');
        $this->createPermission('edit users');
        $this->createPermission('edit newsletter');
    }

    private function createRole(string $name, $permissions): void
    {
        if (!Role::where('name', '=', $name)->exists()) {
            Role::create(['name' => $name]);
        }
        $role = Role::where('name', '=', $name)->firstOrFail();
        $role->givePermissionto($permissions);
    }

    private function makeRoles(): void
    {
        $this->createRole('writer', ['edit posts']);
        $this->createRole('secretary', ['edit canteens', 'edit events', 'edit newsletter']);
        $this->createRole('supervisor', ['edit posts']);
        $this->createRole('admin', Permission::all());
    }

    private function createUser(string $name, string $email, string $password, string $role = null): void
    {
        $user = config('backpack.base.user_model_fqn');
        if (!$user::where('name', '=', $name)->exists()) {
            $user::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        }
        $u = $user::where('name', 'LIKE', 'test')->firstOrFail();
        $u->assignRole('admin');
        $u->save();
    }

    private function makeUsers(): void
    {
        $this->createUser('test', 'test@test.test', 'test', 'admin');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->removeUsers();
        $this->removeRoles();
        $this->removePermissions();
        // is this necessary?
        app()['cache']->forget('spatie.permission.cache');
    }

    public function removePermissions()
    {
        Permission::where('name', '=', 'edit posts')->firstOrFail()->delete();
        Permission::where('name', '=', 'edit events')->firstOrFail()->delete();
        Permission::where('name', '=', 'edit canteens')->firstOrFail()->delete();
        Permission::where('name', '=', 'edit users')->firstOrFail()->delete();
        Permission::where('name', '=', 'edit newsletter')->firstOrFail()->delete();
    }

    private function removeRoles()
    {
        Role::where('name', '=', 'writer')->firstOrFail()->delete();
        Role::where('name', '=', 'secretary')->firstOrFail()->delete();
        Role::where('name', '=', 'supervisor')->firstOrFail()->delete();
        Role::where('name', '=', 'admin')->firstOrFail()->delete();
    }

    private function removeUsers()
    {
        $user = config('backpack.base.user_model_fqn');
        $u = $user::where('name', 'LIKE', 'test')->firstOrFail()->delete();
    }
}
