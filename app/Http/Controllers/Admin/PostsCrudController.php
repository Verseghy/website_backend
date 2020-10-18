<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostsRequest as StoreRequest;
use App\Http\Requests\PostsRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\Posts;
use CRUD;

// VALIDATION: change the requests to match your own file names if you need form validation

/**
 * Class PostsCrudController.
 *
 * @property CrudPanel $crud
 */
class PostsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        CRUD::setModel('App\Models\Posts');
        CRUD::setRoute(config('backpack.base.route_prefix').'/posts');
        CRUD::setEntityNameStrings('posts', 'posts');

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
            'name' => 'color',
            'type' => 'color',
            'label' => 'Color',
        ]);

        CRUD::addColumn([
            'name' => 'index_image',
            'type' => 'image',
            'label' => 'Index image',
            'disk' => 'posts_images',
            'prefix' => 'storage/posts_images/',
        ]);

        CRUD::addColumn([
            'name' => 'author_id',
            'type' => 'select',
            'label' => 'Author',
            'entity' => 'author',
            'attribute' => 'name',
        ]);

        CRUD::addColumn([
            'name' => 'featured',
            'type' => 'boolean',
            'label' => 'Featurable',
        ]);

        // Fields start here

        CRUD::addField([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Title',
            'tab' => 'Properties',
        ]);

        CRUD::addField([
            'name' => 'description',
            'type' => 'textarea',
            'label' => 'Description',
            'tab' => 'Properties',
        ]);

        CRUD::addField([
            'name' => 'content',
            'type' => 'ckeditor',
            'label' => 'Content',
            'tab' => 'Content',
        ]);

        CRUD::addField([
            'name' => 'date',
            'type' => 'date_picker',
            'label' => 'Date',
            'tab' => 'Properties',
        ]);

        CRUD::addField([
            'name' => 'index_image',
            'type' => 'upload',
            'label' => 'Index image',
            'upload' => true,
            'disk' => 'posts_images',
            'tab' => 'Images',
        ]);

        CRUD::addField([   // SelectMultiple = n-n relationship (with pivot table)
            'label' => 'Labels',
            'type' => 'select2_multiple',
            'name' => 'labels', // the method that defines the relationship in your Model
            'entity' => 'labels', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Posts\Labels", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'tab' => 'Properties',
        ]);

        CRUD::addField([
            'name' => 'author_id',
            'type' => 'select',
            'label' => 'Author',
            'entity' => 'author',
            'attribute' => 'name',
            'tab' => 'Properties',
        ]);

        CRUD::addField([
            'name' => 'images',
            'type' => 'upload_multiple',
            'label' => 'Images',
            'upload' => true,
            'disk' => 'posts_images',
            'tab' => 'Images',
        ]);

        CRUD::addField([
            'name' => 'color',
            'type' => 'color_picker',
            'label' => 'Color',
            'tab' => 'Properties',
        ]);

        CRUD::addField([
            'name' => 'featured',
            'type' => 'checkbox',
            'label' => 'Featurable',
            'tab' => 'Publish',
        ]);

        CRUD::addField([
            'name' => 'published',
            'type' => 'checkbox',
            'label' => 'Published',
            'attributes' => $user = backpack_auth()->user()->can('publish posts') ? [] : ['disabled' => ''],
            'tab' => 'Publish',
        ]);

        CRUD::addField([   // URL
            'name' => 'previewLink',
            'label' => 'Preview link:',
            'type' => 'link',
            'tab' => 'Publish',
        ]);

        CRUD::addFilter([
            'type' => 'simple',
            'name' => 'draft',
            'label' => 'Draft',
          ],
          false,
          function () {
              CRUD::addClause('where', 'published', false);
          });

        CRUD::orderBy('date', 'desc');

        // add asterisk for fields that are required in PostsRequest
        CRUD::setRequiredFields(StoreRequest::class, 'create');
        CRUD::setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store()
    {
        if (true == CRUD::getRequest()->input('published') && !backpack_auth()->user->can('publish posts')) {
            return back()->withErrors(['msg' => 'You can not edit published posts or publish posts!'])->withInput();
        }

        $index_image = CRUD::getRequest()->index_image;
        if (is_null($index_image)) {
            return back()->withErrors(['msg' => 'Az indexkép kötelező'])->withInput();
        }

        // your additional operations before save here
        $redirect_location = $this->traitStore();
        // your additional operations after save here
        // use $this->data['entry'] or CRUD::entry
        return $redirect_location;
    }

    public function update()
    {
        $post = Posts::where('id', CRUD::getRequest()->input('id'))->get()->first();

        if (true == CRUD::getRequest()->input('published') || $post->published) {
            if (!backpack_auth()->user()->can('publish posts')) {
                return back()->withErrors(['msg' => 'You can not edit published posts or publish posts!'])->withInput();
            }
        }

        $index_image = CRUD::getRequest()->has('index_image') ? CRUD::getRequest()->index_image : $post->index_image;
        if (is_null($index_image)) {
            return back()->withErrors(['msg' => 'Az indexkép kötelező'])->withInput();
        }

        // your additional operations before save here
        $redirect_location = $this->traitUpdate();
        // your additional operations after save here
        // use $this->data['entry'] or CRUD::entry
        return $redirect_location;
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
