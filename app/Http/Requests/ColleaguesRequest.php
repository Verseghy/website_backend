<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColleaguesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'jobs'=>'string|max:2048|nullable',
            'subjects'=>'string|max:2048|nullable',
            'roles'=>'string|max:2048|nullable',
            'awards'=>'string|max:2048|nullable',
            'menus'=>'required|min:2|max:3',
            'image'=>'image|nullable',
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
            //
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
            //
        ];
    }
}
