<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Validator;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PostsRequest as StoreRequest;
use App\Http\Requests\PostsRequest as UpdateRequest;

/**
 * Class PostsCrudController.
 *
 * @property CrudPanel $crud
 */
class PostsCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Posts');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/posts');
        $this->crud->setEntityNameStrings('posts', 'posts');

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
            'name' => 'color',
            'type' => 'color',
            'label' => 'Color',
        ]);

        $this->crud->addColumn([
            'name' => 'index_image',
            'type' => 'image',
            'label' => 'Index image',
            'disk' => 'posts_images',
            'prefix' => 'storage/posts_images/',
        ]);

        $this->crud->addColumn([
            'name' => 'author_id',
            'type' => 'select',
            'label' => 'Author',
            'entity' => 'author',
            'attribute' => 'name',
        ]);

        $this->crud->addColumn([
            'name'=>'featured',
            'type'=>'boolean',
            'label'=>'Featurable',
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
            'name' => 'featured',
            'type' => 'checkbox',
            'label' => 'Featurable',
        ]);

        $this->crud->addField([
            'name' => 'content',
            'type' => 'simplemde',
            'label' => 'Content',
            'simplemdeAttributes' => [
                'promptURLs' => true,
                'status' => true,
                'spellChecker' => false,
                'forceSync' => true,
            ],
        ]);

        $this->crud->addField([
            'name' => 'date',
            'type' => 'date',
            'label' => 'Date',
        ]);

        $this->crud->addField([
            'name' => 'index_image',
            'type' => 'upload',
            'label' => 'Index image',
            'upload' => true,
            'disk' => 'posts_images',
        ]);

        $this->crud->addField([   // SelectMultiple = n-n relationship (with pivot table)
            'label' => 'Labels',
            'type' => 'select2_multiple',
            'name' => 'labels', // the method that defines the relationship in your Model
            'entity' => 'labels', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Posts\Labels", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
        ]);

        $this->crud->addField([
            'name' => 'author_id',
            'type' => 'select',
            'label' => 'Author',
            'entity' => 'author',
            'attribute' => 'name',
        ]);

        $this->crud->addField([
            'name' => 'images',
            'type' => 'upload_multiple',
            'label' => 'Images',
            'upload' => true,
            'disk' => 'posts_images',
        ]);

        $this->crud->addField([
            'name' => 'color',
            'type' => 'color',
            'label' => 'Color',
        ]);

        $this->crud->addField([
            'name' => 'type',
            'type' => 'select2_from_array',
            'label' => 'Type',
            'options' => [
                1 => 'No image',
                2 => 'With image',
                3 => 'Only image',
            ],
            'allows_null' => false,
        ]);

        // add asterisk for fields that are required in PostsRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'index_image' => 'required',
        ], [
            'required' => 'The :attribute field is required for featured posts!',
        ]);
        if ($request->featured==true and $request->index_image==null) {
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
        }
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {

        $validator = Validator::make($request->all(), [
            'index_image' => 'required',
        ], [
            'required' => 'The :attribute field is required for featured posts!',
        ]);
        if ($request->featured==true and $request->index_image==null) {
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
        }
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
