<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostsRequest as StoreRequest;
use App\Http\Requests\PostsRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\Posts;

// VALIDATION: change the requests to match your own file names if you need form validation

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
            'name' => 'featured',
            'type' => 'boolean',
            'label' => 'Featurable',
        ]);

        // Fields start here

        $this->crud->addField([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Title',
            'tab' => 'Properties',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'type' => 'textarea',
            'label' => 'Description',
            'tab' => 'Properties',
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
            'tab' => 'Content',
        ]);

        $this->crud->addField([
            'name' => 'date',
            'type' => 'date_picker',
            'label' => 'Date',
            'tab' => 'Properties',
        ]);

        $this->crud->addField([
            'name' => 'index_image',
            'type' => 'upload',
            'label' => 'Index image',
            'upload' => true,
            'disk' => 'posts_images',
            'tab' => 'Images',
        ]);

        $this->crud->addField([   // SelectMultiple = n-n relationship (with pivot table)
            'label' => 'Labels',
            'type' => 'select2_multiple',
            'name' => 'labels', // the method that defines the relationship in your Model
            'entity' => 'labels', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Posts\Labels", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'tab' => 'Properties',
        ]);

        $this->crud->addField([
            'name' => 'author_id',
            'type' => 'select',
            'label' => 'Author',
            'entity' => 'author',
            'attribute' => 'name',
            'tab' => 'Properties',
        ]);

        $this->crud->addField([
            'name' => 'images',
            'type' => 'upload_multiple',
            'label' => 'Images',
            'upload' => true,
            'disk' => 'posts_images',
            'tab' => 'Images',
        ]);

        $this->crud->addField([
            'name' => 'color',
            'type' => 'color_picker',
            'label' => 'Color',
            'tab' => 'Properties',
        ]);

        $this->crud->addField([
            'name' => 'type',
            'type' => 'select2_from_array',
            'label' => 'Type',
            'options' => [
                0 => 'No image',
                1 => 'Image in background',
                2 => 'With Image',
            ],
            'allows_null' => false,
            'tab' => 'Images',
        ]);

        $this->crud->addField([
            'name' => 'featured',
            'type' => 'checkbox',
            'label' => 'Featurable',
            'tab' => 'Publish',
        ]);

        $this->crud->addField([
            'name' => 'published',
            'type' => 'checkbox',
            'label' => 'Published',
            'attributes' => $user = auth()->user()->can('publish posts') ? [] : ['disabled' => ''],
            'tab' => 'Publish',
        ]);

        $this->crud->addField([   // URL
            'name' => 'previewLink',
            'label' => 'Preview link:',
            'type' => 'link',
            'tab' => 'Publish',
        ]);

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'draft',
            'label' => 'Draft',
          ],
          false,
          function () {
              $this->crud->addClause('where', 'published', false);
          });

        $this->crud->orderBy('date', 'desc');

        // add asterisk for fields that are required in PostsRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        if (true == $request->input('published') && !auth()->user->can('publish posts')) {
            return back()->withErrors(['msg' => 'You can not edit published posts or publish posts!'])->withInput();
        }

        if (true == $request->featured) {
            $index_image = $request->index_image;
            if (is_null($index_image)) {
                return back()->withErrors(['msg' => 'A featured post must have an index image!'])->withInput();
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
        $post = Posts::where('id', $request->input('id'))->get()->first();

        $featured = $request->has('featured') ? $request->featured : $post->featured;
        if (true == $featured) {
            $index_image = $request->has('index_image') ? $request->index_image : $post->index_image;
            if (is_null($index_image)) {
                return back()->withErrors(['msg' => 'A featured post must have an index image!'])->withInput();
            }
        }

        if (true == $request->input('published') || $post->published) {
            if (!auth()->user()->can('publish posts')) {
                return back()->withErrors(['msg' => 'You can not edit published posts or publish posts!'])->withInput();
            }
        }

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
