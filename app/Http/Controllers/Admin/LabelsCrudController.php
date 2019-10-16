<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LabelsRequest as StoreRequest;
use App\Http\Requests\LabelsRequest as UpdateRequest;

/**
 * Class LabelsCrudController.
 *
 * @property CrudPanel $crud
 */
class LabelsCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Posts\Labels');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/labels');
        $this->crud->setEntityNameStrings('labels', 'labels');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Label name',
        ]);

        $this->crud->addColumn([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Label name',
        ]);

        $this->crud->addField([
            'name' => 'color',
            'type' => 'color_picker',
            'label' => 'Label color',
        ]);

        $this->crud->addColumn([
            'name' => 'color',
            'type' => 'color',
            'label' => 'Label color',
        ]);

        // add asterisk for fields that are required in LabelsRequest
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
