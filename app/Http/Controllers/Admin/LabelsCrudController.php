<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LabelsRequest as StoreRequest;
use App\Http\Requests\LabelsRequest as UpdateRequest;
use CRUD;

/**
 * Class LabelsCrudController.
 *
 * @property CrudPanel $crud
 */
class LabelsCrudController extends CrudController
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
        CRUD::setModel('App\Models\Posts\Labels');
        CRUD::setRoute(config('backpack.base.route_prefix').'/labels');
        CRUD::setEntityNameStrings('labels', 'labels');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        CRUD::addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Label name',
        ]);

        CRUD::addColumn([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Label name',
        ]);

        CRUD::addField([
            'name' => 'color',
            'type' => 'color_picker',
            'label' => 'Label color',
        ]);

        CRUD::addColumn([
            'name' => 'color',
            'type' => 'color',
            'label' => 'Label color',
        ]);

        CRUD::orderBy('name');

        // add asterisk for fields that are required in LabelsRequest
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
