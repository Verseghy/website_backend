<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ColleaguesRequest as StoreRequest;
use App\Http\Requests\ColleaguesRequest as UpdateRequest;

/**
 * Class AuthorsCrudController.
 *
 * @property CrudPanel $crud
 */
class ColleaguesCrudController extends CrudController
{
    use AuthDestroy;
    protected $destroyRequestClass = UpdateRequest::class;

    public function setup()
    {
        $this->crud->setModel('App\Models\Colleagues');
        $this->crud->setRoute(config('backpack.base.route_prefix').'/colleagues');
        $this->crud->setEntityNameStrings('colleagues', 'colleagues');

        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'jobs',
            'label' => 'Jobs',
            'type' => 'textarea',
        ]);

        $this->crud->addColumn([
            'name' => 'image',
            'label' => 'Image',
            'type' => 'image',
            'prefix' => 'storage/colleagues_images/',
            'disk' => 'colleagues_images',
        ]);

        $this->crud->addColumn([
            'name' => 'category',
            'label' => 'Category',
            'type' => 'select_from_array',
            'options' => [0 => 'Brass', 1 => 'Teachers', 2 => 'Lecturer', 3 => 'Administrator', 4 => 'Kitchen Staff', 5 => 'Maintenance Staff'],
            'allows_null' => false,
        ]);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'name' => 'image',
            'label' => 'Profile image',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'colleagues_images',
        ]);

        $this->crud->addField([
            'name' => 'jobs',
            'label' => 'Jobs',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'name' => 'subjects',
            'label' => 'Subjects',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'name' => 'roles',
            'label' => 'Roles',
            'type' => 'textarea',
        ]);

        $this->crud->addField([
            'name' => 'category',
            'label' => 'Category',
            'type' => 'select_from_array',
            'options' => [0 => 'Brass', 1 => 'Teachers', 2 => 'lecturer', 3 => 'Administrator', 4 => 'Kitchen Staff', 5 => 'Maintenance Staff'],
            'allows_null' => false,
        ]);

        $this->crud->addField([
            'name' => 'awards',
            'label' => 'Awards',
            'type' => 'textarea',
        ]);

        $this->crud->orderBy('name');

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
