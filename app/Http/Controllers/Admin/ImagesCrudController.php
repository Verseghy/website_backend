<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ImagesRequest as StoreRequest;
use App\Http\Requests\ImagesRequest as UpdateRequest;

/**
 * Class ImagesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ImagesCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Posts\Images');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/images');
        $this->crud->setEntityNameStrings('images', 'images');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'url',
            'label' => 'Image',
            'type' => 'image',
            'height'=>'100px',
            'width'=>'100px',
        ]);
        
        $this->crud->addColumn([
            'label' => "Post",
            'type' => 'select',
            'name' => 'post_id', // the db column for the foreign key
            'entity' => 'post', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "App\Models\Posts" // foreign key model
        ]);
        
        
        $this->crud->addField([
            'name'=>'url',
            'label'=>'Image',
            'type'=>'image',
            'upload'=>true,
        ]);
        
        $this->crud->addField([
            'label' => "Post",
            'type' => 'select2',
            'name' => 'post_id', // the db column for the foreign key
            'entity' => 'post', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "App\Models\Posts" // foreign key model
        ]);

        // add asterisk for fields that are required in ImagesRequest
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
