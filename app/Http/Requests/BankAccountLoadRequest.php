<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountLoadRequest extends FormRequest
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
            'credit_card' => 'required|integer',
            'cvv' => 'required|max:4|min:3',
            'name' => 'required',
            'expiry_date' => 'required|max:5|min:5',
            'amount' => 'required'
        ];
    }

    public function number()
    {
        $prefix = str_pad('', strlen($this->credit_card) - 10, 'X');

        $bin = substr($this->credit_card, 0, 6);
        $holder = substr($this->credit_card, -4);

        return $bin . $prefix . $holder;
    }
}
