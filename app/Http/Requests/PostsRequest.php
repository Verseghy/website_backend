<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;


class PostsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
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
            'title' => 'required|string|max:255',
            'color'=> 'required|string|min:7|max:7|regex:/(^#[0-9a-fA-F]{6}$)/u',
            'description' => 'string|nullable|max:1024',
            'content'=> 'string|nullable',
            'index_image'=>'image|nullable',
            'author_id'=>'integer|min:1|nullable',
            'date'=>'date|nullable',
            'type'=>'integer|min:0|max:2|nullable',
            'images'=>'nullable',
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
