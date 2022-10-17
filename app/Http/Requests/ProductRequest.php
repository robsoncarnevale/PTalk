<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date_begin' => 'required|date',
            'date_finish' => 'required|date',
            'itens' => 'required|min:1',
            'name' => 'required|max:50',
            'description' => 'required|max:200',
            'value' => 'required|min:1',
            'img_url' => 'required'
        ];
    }
}
