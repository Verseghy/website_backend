<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ColleaguesRequest as StoreRequest;
use App\Http\Requests\ColleaguesRequest as UpdateRequest;
use CRUD;

/**
 * Class AuthorsCrudController.
 *
 * @property CrudPanel $crud
 */
class ColleaguesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        CRUD::setModel('App\Models\Colleagues');
        CRUD::setRoute(config('backpack.base.route_prefix').'/colleagues');
        CRUD::setEntityNameStrings('colleagues', 'colleagues');

        CRUD::addColumn([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'jobs',
            'label' => 'Jobs',
            'type' => 'textarea',
        ]);

        CRUD::addColumn([
            'name' => 'image',
            'label' => 'Image',
            'type' => 'image',
            'prefix' => 'storage/colleagues_images/',
            'disk' => 'colleagues_images',
        ]);

        CRUD::addColumn([
            'name' => 'category',
            'label' => 'Category',
            'type' => 'select_from_array',
            'options' => [0 => 'Brass', 1 => 'Teachers', 2 => 'Lecturer', 3 => 'Administrator', 4 => 'Maintenance Staff'],
            'allows_null' => false,
        ]);

        CRUD::addField([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'image',
            'label' => 'Profile image',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'colleagues_images',
        ]);

        CRUD::addField([
            'name' => 'jobs',
            'label' => 'Jobs',
            'type' => 'textarea',
        ]);

        CRUD::addField([
            'name' => 'subjects',
            'label' => 'Subjects',
            'type' => 'textarea',
        ]);

        CRUD::addField([
            'name' => 'roles',
            'label' => 'Roles',
            'type' => 'textarea',
        ]);

        CRUD::addField([
            'name' => 'category',
            'label' => 'Category',
            'type' => 'select_from_array',
            'options' => [0 => 'Brass', 1 => 'Teachers', 2 => 'Lecturer', 3 => 'Administrator', 4 => 'Maintenance Staff'],
            'allows_null' => false,
        ]);

        CRUD::addField([
            'name' => 'awards',
            'label' => 'Awards',
            'type' => 'textarea',
        ]);

        CRUD::orderBy('name');

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
