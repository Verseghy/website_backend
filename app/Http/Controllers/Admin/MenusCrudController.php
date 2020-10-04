<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MenusRequest as StoreRequest;
use App\Http\Requests\MenusRequest as UpdateRequest;
use CRUD;

/**
 * Class MenusCrudController.
 *
 * @property CrudPanel $crud
 */
class MenusCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        CRUD::setModel('App\Models\Canteens\Menus');
        CRUD::setRoute(config('backpack.base.route_prefix').'/menus');
        CRUD::setEntityNameStrings('menu', 'menus');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        CRUD::addColumn([
            'name' => 'menu',
            'label' => 'Name',
            'type' => 'text',
        ]);

        CRUD::addColumn([   // select_from_array
            'name' => 'type',
            'label' => 'Type',
            'type' => 'select_from_array',
            'options' => [0 => 'Soup', 1 => 'Main dish', 2 => 'Dessert'],
            'allows_null' => false,
        ]);

        CRUD::addField([
            'name' => 'menu',
            'label' => 'Name',
            'type' => 'text',
        ]);

        CRUD::addField([   // select_from_array
            'name' => 'type',
            'label' => 'Type',
            'type' => 'select2_from_array',
            'options' => [0 => 'Soup', 1 => 'Main dish', 2 => 'Dessert'],
            'allows_null' => false,
        ]);

        CRUD::orderBy('menu');

        // add asterisk for fields that are required in MenusRequest
        CRUD::setRequiredFields(StoreRequest::class, 'create');
        CRUD::setRequiredFields(UpdateRequest::class, 'edit');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(UpdateRequest::class);
    }
}
