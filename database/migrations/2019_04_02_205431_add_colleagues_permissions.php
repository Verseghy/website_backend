<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddColleaguesPermissions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');
        $this->createPermission('edit colleagues');
        Role::where('name', '=', 'secretary')->firstOrFail()->givePermissionTo('edit colleagues');
        Role::where('name', '=', 'admin')->firstOrFail()->givePermissionTo('edit colleagues');
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
        Role::where('name', '=', 'secretary')->firstOrFail()->revokePermissionTo('edit colleagues');
        Role::where('name', '=', 'admin')->firstOrFail()->revokePermissionTo('edit colleagues');
        Permission::where('name', '=', 'edit colleagues')->firstOrFail()->delete();
        // is this necessary?
        app()['cache']->forget('spatie.permission.cache');
    }
}
