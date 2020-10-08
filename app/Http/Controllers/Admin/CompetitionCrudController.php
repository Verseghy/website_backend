<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CompetitionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CompetitionCrudController.
 *
 * @property \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CompetitionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Competition::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/competition');
        CRUD::setEntityNameStrings('competition', 'competitions');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     *
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name')->type('string');
        CRUD::column('slug')->type('string');
        CRUD::column('year')->type('string');

        /*
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     *
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CompetitionRequest::class);

        /*
         * General info fields
         */
        CRUD::field('name')->type('text')->tab('Info');
        CRUD::field('slug')->type('text')->tab('Info');
        CRUD::field('year')->type('number')->tab('Info');

        /*
         * CardView
         */
        CRUD::field('shortDescription')->fake(true)->store_in('card')->tab('Card');
        CRUD::addField([
            'name' => 'image',
            'type' => 'base64_image',
            'fake' => 'true',
            'store_in' => 'card',
            'tab' => 'Card',
            'filename' => null,
        ]);

        /*
         * HeroView
         * TODO(zoltanszepesi): pre post during after missing!!!!
         */
        /*CRUD::field('shortDescription')->fake(true)->store_in('card')->tab('Card');*/
        CRUD::field('description')->type('ckeditor')->fake(true)->store_in('hero')->tab('Hero');

        /*
         * AboutView
         */
        CRUD::field('description')->type('ckeditor')->fake(true)->store_in('about')->tab('About');

        /*
         * Schedule
         */
        CRUD::addField([
            'name' => 'rounds',
            'type' => 'repeatable',
            'fake' => true,
            'store_in' => 'schedule',
            'tab' => 'Schedule',

            'fields' => [
                [
                    'name' => 'name',
                    'type' => 'text',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'start',
                    'type' => 'date',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'end',
                    'type' => 'date',
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'description',
                    'type' => 'ckeditor',
                ],
            ],
        ]);
        CRUD::field('rounds')->type('repeatable')->fake(true)->store_in('schedule')->tab('Schedule');

        /*
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     *
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
