<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddPostsPublishPermissions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');
        $this->createPermission('publish posts');
        Role::where('name', '=', 'admin')->firstOrFail()->givePermissionTo('publish posts');
        Role::where('name', '=', 'supervisor')->firstOrFail()->givePermissionTo('publish posts');

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
        Role::where('name', '=', 'admin')->firstOrFail()->revokePermissionTo('publish posts');
        Role::where('name', '=', 'supervisor')->firstOrFail()->revokePermissionTo('publish posts');
        Permission::where('name', '=', 'publish posts')->firstOrFail()->delete();
        // is this necessary?
        app()['cache']->forget('spatie.permission.cache');
    }
}
