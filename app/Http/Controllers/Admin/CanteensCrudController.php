<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CanteensRequest as StoreRequest;
use App\Http\Requests\CanteensRequest as UpdateRequest;
use CRUD;

/**
 * Class CanteensCrudController.
 *
 * @property CrudPanel $crud
 */
class CanteensCrudController extends CrudController
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
        CRUD::setModel('App\Models\Canteens');
        CRUD::setRoute(config('backpack.base.route_prefix').'/canteens');
        CRUD::setEntityNameStrings('canteens', 'canteens');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        CRUD::addColumn([
            'name' => 'date',
            'type' => 'text',
            'label' => 'Date',
        ]);

        CRUD::addColumn([   // Select2Multiple = n-n relationship (with pivot table)
            'label' => 'Meals',
            'type' => 'select_multiple',
            'name' => 'menus', // the method that defines the relationship in your Model
            'entity' => 'menus', // the method that defines the relationship in your Model
            'attribute' => 'menu', // foreign key attribute that is shown to user
            'model' => "App\Models\Canteens\Menus", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);

        CRUD::addField([   // Select2Multiple = n-n relationship (with pivot table)
            'label' => 'Meals',
            'type' => 'select2_multiple',
            'name' => 'menus', // the method that defines the relationship in your Model
            'entity' => 'menus', // the method that defines the relationship in your Model
            'attribute' => 'menu', // foreign key attribute that is shown to user
            'model' => "App\Models\Canteens\Menus", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);

        CRUD::addField([
            'name' => 'date',
            'type' => 'date',
            'label' => 'Date',
        ]);

        CRUD::orderBy('date', 'desc');

        // add asterisk for fields that are required in CanteensRequest
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
