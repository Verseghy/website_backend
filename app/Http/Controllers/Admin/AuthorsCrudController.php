<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AuthorsRequest as StoreRequest;
use App\Http\Requests\AuthorsRequest as UpdateRequest;

/**
 * Class AuthorsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AuthorsCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Posts\Authors');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/authors');
        $this->crud->setEntityNameStrings('authors', 'authors');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addColumn([
            'name'=>'name',
            'label'=>'Name',
            'type'=>'text',
        ]);
        
        $this->crud->addColumn([
            'name'=>'description',
            'label'=>'Description',
            'type'=>'text',
        ]);
        
        $this->crud->addColumn([
            'name'=>'image',
            'label'=>'Profile image',
            'type'=>'image',
            'prefix'=>'storage/authors_images/'
        ]);


        $this->crud->addField([
            'name'=>'name',
            'label'=>'Name',
            'type'=>'text',
        ]);
        
        $this->crud->addField([
            'name'=>'description',
            'label'=>'Description',
            'type'=>'textarea',
        ]);
        
        $this->crud->addField([
            'name'=>'image',
            'label'=>'Profile image',
            'type'=>'upload',
            'upload'=>true,
            'disk'=>'authors_images',
        ]);
        
        // add asterisk for fields that are required in AuthorsRequest
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
