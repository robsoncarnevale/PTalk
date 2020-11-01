<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlacklistRequest extends FormRequest
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
        if ($this::method() == 'PUT')
            return $this->createRules();

        if ($this::method() == 'POST')
            return $this->updateRules();
    }

    private function createRules()
    {
        return [
            'phone'  =>  [ 'required' , 'min:8', 'max:11', new \App\Rules\NotBlacklistExists() ],
            'description' => 'required|max:200',
            'status' => 'required',
        ];
    }

    private function updateRules()
    {
        return [
            'description' => 'required|max:200',
            'status' => 'required',
        ];
    }
}
