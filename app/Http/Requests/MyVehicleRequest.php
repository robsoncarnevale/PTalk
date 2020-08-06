<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyVehicleRequest extends FormRequest
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
        if ($this::method() == 'PUT')
            return $this->createRules();

        if ($this::method() == 'POST')
            return $this->updateRules();
    }

    private function createRules()
    {
        return [
            'car_model_id' => [ 'required', new \App\Rules\CarModelExists() ],
        ];
    }

    private function updateRules()
    {
        return [
            'car_model_id' => [ 'sometimes', new \App\Rules\CarModelExists() ],
        ];
    }
}
