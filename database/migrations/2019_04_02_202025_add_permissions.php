<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');
        $this->makePermissions();
        $this->makeRoles();
        $this->makeUsers();
    }

    public function makePermissions()
    {
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'edit events']);
        Permission::create(['name' => 'edit canteens']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'edit newsletter']);
    }

    private function makeRoles()
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

    private function makeUsers()
    {
        $user = config('backpack.base.user_model_fqn');
        (new $user([
            'name' => 'test',
            'email' => 'test@test.test',
            'password' => Hash::make('test'),
        ]))->save();
        $u = $user::where('name', 'LIKE', 'test')->firstOrFail();
        $u->assignRole('admin');
        $u->save();
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
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
