<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\NewsletterRequest as StoreRequest;
use App\Http\Requests\NewsletterRequest as UpdateRequest;

/**
 * Class NewsletterCrudController.
 *
 * @property CrudPanel $crud
 */
class NewsletterCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Newsletter');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/newsletter');
        $this->crud->setEntityNameStrings('newsletter', 'newsletters');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
        ]);

        $this->crud->addField([
            'name' => 'token',
            'label' => 'Token',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
        ]);

        // add asterisk for fields that are required in NewsletterRequest
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
