<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        if ($this::method() == 'PUT') {
            return $this->createRules();
        }

        if ($this::method() == 'POST') {
            return $this->updateRules();
        }

        return [];
    }

    private function createRules()
    {
        return [
            'document_cpf'  =>  [ 'required' , 'size:11', new \App\Rules\ValidCpf() ],
            'name'  =>  'required',
            'phone'  =>  'required|min:8|max:11',
            'email'  =>  'required|email',
            'nickname' => 'max:20',
        ];
    }

    private function updateRules()
    {
        return [
            'document_cpf'  =>  [ 'size:11', new \App\Rules\ValidCpf() ],
            'phone'  =>  'min:8|max:11',
            'email'  =>  'email',
            'nickname' => 'max:20',
        ];
    }
}
