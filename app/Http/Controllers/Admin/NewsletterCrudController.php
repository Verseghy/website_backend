<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\NewsletterRequest as StoreRequest;
use App\Http\Requests\NewsletterRequest as UpdateRequest;
use CRUD;

/**
 * Class NewsletterCrudController.
 *
 * @property CrudPanel $crud
 */
class NewsletterCrudController extends CrudController
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
        CRUD::setModel('App\Models\Newsletter');
        CRUD::setRoute(config('backpack.base.route_prefix').'/newsletter');
        CRUD::setEntityNameStrings('newsletter', 'newsletters');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        CRUD::addField([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
        ]);

        CRUD::addField([
            'name' => 'token',
            'label' => 'Token',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
        ]);

        // add asterisk for fields that are required in NewsletterRequest
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
