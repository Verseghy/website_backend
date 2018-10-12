<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CanteensRequest as StoreRequest;
use App\Http\Requests\CanteensRequest as UpdateRequest;

/**
 * Class CanteensCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CanteensCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Canteens');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/canteens');
        $this->crud->setEntityNameStrings('canteens', 'canteens');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name'=>'date',
            'type'=>'text',
            'label'=>'Date',
        ]);

        
        $this->crud->addColumn([   // Select2Multiple = n-n relationship (with pivot table)
            'label' => 'Meals',
            'type' => 'select_multiple',
            'name' => 'menus', // the method that defines the relationship in your Model
            'entity' => 'menus', // the method that defines the relationship in your Model
            'attribute' => 'menu', // foreign key attribute that is shown to user
            'model' => "App\Models\Canteens\Menus", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);
        
        $this->crud->addField([   // Select2Multiple = n-n relationship (with pivot table)
            'label' => 'Meals',
            'type' => 'select2_multiple',
            'name' => 'menus', // the method that defines the relationship in your Model
            'entity' => 'menus', // the method that defines the relationship in your Model
            'attribute' => 'menu', // foreign key attribute that is shown to user
            'model' => "App\Models\Canteens\Menus", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);

        $this->crud->addField([
            'name'=>'date',
            'type'=>'date',
            'label'=>'Date',
        ]);

        // add asterisk for fields that are required in CanteensRequest
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
