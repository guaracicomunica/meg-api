<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EnrollClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasVerifiedEmail();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => [
                'required',
                'string',
                Rule::exists('classrooms', 'code')
                    ->where(function($query){
                        $query->where('status', 1);
                    })
            ],
        ];
    }


    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => 'O preenchimento do código da turma é necessário',
            'code.string' => 'O código da turma deve estar em formato de texto',
            'code.exists' => 'O código não existe ou está associado a uma turma inativa',
        ];
    }
}
