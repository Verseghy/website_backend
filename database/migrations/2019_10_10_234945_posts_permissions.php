<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PostsPermissions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');
        $this->createPermission('edit labels');
        $this->createPermission('edit authors');
        Role::where('name', '=', 'admin')->firstOrFail()->givePermissionTo('edit labels');
        Role::where('name', '=', 'admin')->firstOrFail()->givePermissionTo('edit authors');
        Role::where('name', '=', 'writer')->firstOrFail()->givePermissionTo('edit labels');
        Role::where('name', '=', 'writer')->firstOrFail()->givePermissionTo('edit authors');
        Role::where('name', '=', 'supervisor')->firstOrFail()->givePermissionTo('edit labels');
        Role::where('name', '=', 'supervisor')->firstOrFail()->givePermissionTo('edit authors');
    }

    private function createPermission(string $name): void
    {
        if (!Permission::where('name', '=', $name)->exists()) {
            Permission::create(['name' => $name]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Role::where('name', '=', 'admin')->firstOrFail()->revokePermissionTo('edit labels');
        Role::where('name', '=', 'admin')->firstOrFail()->revokePermissionTo('edit authors');
        Role::where('name', '=', 'writer')->firstOrFail()->revokePermissionTo('edit labels');
        Role::where('name', '=', 'writer')->firstOrFail()->revokePermissionTo('edit authors');
        Role::where('name', '=', 'supervisor')->firstOrFail()->revokePermissionTo('edit labels');
        Role::where('name', '=', 'supervisor')->firstOrFail()->revokePermissionTo('edit authors');
        Permission::where('name', '=', 'edit labels')->firstOrFail()->delete();
        Permission::where('name', '=', 'edit authors')->firstOrFail()->delete();
        // is this necessary?
        app()['cache']->forget('spatie.permission.cache');
    }
}
