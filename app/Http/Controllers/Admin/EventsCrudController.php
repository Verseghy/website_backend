<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EventsRequest as StoreRequest;
use App\Http\Requests\EventsRequest as UpdateRequest;
use CRUD;

/**
 * Class EventsCrudController.
 *
 * @property CrudPanel $crud
 */
class EventsCrudController extends CrudController
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
        CRUD::setModel('App\Models\Events');
        CRUD::setRoute(config('backpack.base.route_prefix').'/events');
        CRUD::setEntityNameStrings('events', 'events');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        CRUD::addColumn([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Title',
        ]);

        CRUD::addColumn([
            'name' => 'description',
            'type' => 'text',
            'label' => 'Description',
        ]);

        CRUD::addColumn([
            'name' => 'date_from',
            'type' => 'text',
            'label' => 'Starts',
        ]);

        CRUD::addColumn([
            'name' => 'date_to',
            'type' => 'text',
            'label' => 'Ends',
        ]);

        CRUD::addField([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Title',
        ]);

        CRUD::addField([
            'name' => 'description',
            'type' => 'textarea',
            'label' => 'Description',
        ]);

        CRUD::addField([
            'name' => 'date_from',
            'label' => 'Event start',
            'type' => 'datetime_picker',
            // optional:
            'datetime_picker_options' => [
                'format' => 'YYYY/MM/DD HH:mm',
                'language' => 'hu',
            ],
        ]);
        CRUD::addField([
            'name' => 'date_to',
            'label' => 'Event end',
            'type' => 'datetime_picker',
            // optional:
            'datetime_picker_options' => [
                'format' => 'YYYY/MM/DD HH:mm',
                'language' => 'hu',
            ],
        ]);

        CRUD::addField([
            'name' => 'color',
            'label' => 'color',
            'type' => 'color_picker',
        ]);

        CRUD::orderBy('date_from', 'desc');

        // add asterisk for fields that are required in EventsRequest
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
