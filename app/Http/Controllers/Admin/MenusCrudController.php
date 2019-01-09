<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MenusRequest as StoreRequest;
use App\Http\Requests\MenusRequest as UpdateRequest;

/**
 * Class MenusCrudController.
 *
 * @property CrudPanel $crud
 */
class MenusCrudController extends CrudController
{
    use AuthDestroy;
    protected $destroyRequestClass = UpdateRequest::class;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Canteens\Menus');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/menus');
        $this->crud->setEntityNameStrings('menu', 'menus');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'menu',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->crud->addColumn([   // select_from_array
            'name' => 'type',
            'label' => 'Type',
            'type' => 'select_from_array',
            'options' => [0 => 'Soup', 1 => 'Main dish', 2 => 'Dessert'],
            'allows_null' => false,
        ]);

        $this->crud->addField([
            'name' => 'menu',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->crud->addField([   // select_from_array
            'name' => 'type',
            'label' => 'Type',
            'type' => 'select2_from_array',
            'options' => [0 => 'Soup', 1 => 'Main dish', 2 => 'Dessert'],
            'allows_null' => false,
        ]);

        // add asterisk for fields that are required in MenusRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
