<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCpf;
use App\Rules\CarPlateRule;

class RequestParticipationRequest extends FormRequest
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
            'phone' => 'string|required|min:11|max:15',
            'name' => 'string|required',
            'email' => 'email|required',
            'document_cpf' => ['required', new ValidCpf], 
            'vehicle_carplate' => ['required', new CarPlateRule],
            'vehicle_photo' => 'file|required'
        ];
    }
}
