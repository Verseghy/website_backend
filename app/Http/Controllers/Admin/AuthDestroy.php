<?php
namespace App\Http\Controllers\Admin;

trait AuthDestroy
{
    public function destroy($id)
    {
        $r = new $this->destroyRequestClass;
        if ($r->authorize())
        {
            parent::destroy(null);
        }
        else
        {
            //\Alert::error("You do not have the permission to do that")->flash();
            //return redirect($this->crud->route);
            return response()->json(['You do not have permission to do that'],403);
        }
    }
}

