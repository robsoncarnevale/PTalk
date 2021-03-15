<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        if ($this->has('document_cpf')) {
            $this->merge([ 'document_cpf' => preg_replace("#[^0-9]*#is", "", $this->document_cpf) ]);
        }

        if ($this->has('phone')) {
            $this->merge([ 'phone' => preg_replace("#[^0-9]*#is", "", $this->phone) ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'document_cpf'  =>  [ 'size:11', new \App\Rules\ValidCpf() ],
            'nickname' => 'max:20',
        ];
    }
}
