<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
        if ($this->has('start_time')) {
            $this->merge([ 'start_time' => preg_replace("#[^0-9]*#is", "", $this->start_time) ]);
        }

        if ($this->has('end_time')) {
            $this->merge([ 'end_time' => preg_replace("#[^0-9]*#is", "", $this->end_time) ]);
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

        return [];
    }

    private function createRules()
    {
        return array_merge([
            'name' => 'required',

            'max_vehicles' => 'integer|min:1|max:9999|nullable',
            'max_participants' => 'integer|min:1|max:9999|nullable',
            'max_companions' => 'integer|min:1|max:9999|nullable',

            'start_time' => 'numeric|nullable',
            'end_time' => 'numeric|nullable',

            'date' => 'date_format:d/m/Y',
            'date_limit' => 'date_format:d/m/Y|after:date',
        ], $this->getClassValidation());
    }

    private function updateRules()
    {
        

        return array_merge([
            'name' => 'required',

            'max_vehicles' => 'integer|min:1|max:9999|nullable',
            'max_participants' => 'integer|min:1|max:9999|nullable',
            'max_companions' => 'integer|min:1|max:9999|nullable',

            'start_time' => 'numeric|nullable',
            'end_time' => 'numeric|nullable',

            'date' => 'date_format:d/m/Y',
            'date_limit' => 'date_format:d/m/Y|before:date',
        ], $this->getClassValidation());
    }

    private static function getClassValidation()
    {
        $class_validation = array();
        $classes = \App\Models\MemberClass::select()
            ->where('club_code', getClubCode())
            ->get()
            ->toArray();

        foreach($classes as $member_class) {
            $validation_init = 'class.' . $member_class['label'] . '.';

            $class_validation[$validation_init . 'start_subscription_date'] = 'date_format:d/m/Y|before:date|nullable';
        } 

        return $class_validation;
    }
}
