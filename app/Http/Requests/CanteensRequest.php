<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CanteensRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check() && backpack_auth()->user()->can('edit canteens');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'date' => 'required|date',
           'menus' => 'required|min:2|max:3',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'menus.min' => 'Legalább két menü hozzáadása szükséges',
            'menus.max' => 'Legfeljebb három menü hozzáadása lehetséges',
        ];
    }
}
