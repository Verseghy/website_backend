<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EventsRequest as StoreRequest;
use App\Http\Requests\EventsRequest as UpdateRequest;

/**
 * Class EventsCrudController.
 *
 * @property CrudPanel $crud
 */
class EventsCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Events');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/events');
        $this->crud->setEntityNameStrings('events', 'events');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Title',
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'type' => 'text',
            'label' => 'Description',
        ]);

        $this->crud->addColumn([
            'name' => 'date_from',
            'type' => 'text',
            'label' => 'Starts',
        ]);

        $this->crud->addColumn([
            'name' => 'date_to',
            'type' => 'text',
            'label' => 'Ends',
        ]);

        $this->crud->addField([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Title',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'type' => 'textarea',
            'label' => 'Description',
        ]);

        $this->crud->addField([
            'name' => 'date_from',
            'label' => 'Event start',
            'type' => 'datetime_picker',
            // optional:
            'datetime_picker_options' => [
                'format' => 'YYYY/MM/DD HH:mm',
                'language' => 'hu',
            ],
        ]);
        $this->crud->addField([
            'name' => 'date_to',
            'label' => 'Event end',
            'type' => 'datetime_picker',
            // optional:
            'datetime_picker_options' => [
                'format' => 'YYYY/MM/DD HH:mm',
                'language' => 'hu',
            ],
        ]);

        $this->crud->addField([
            'name' => 'color',
            'label' => 'color',
            'type' => 'color_picker',
        ]);

        $this->crud->orderBy('date_from', 'desc');

        // add asterisk for fields that are required in EventsRequest
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
