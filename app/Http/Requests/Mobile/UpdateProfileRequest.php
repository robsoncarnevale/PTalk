<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'email'  =>  'email',
            'nickname' => 'max:20',
        ];
    }
}
