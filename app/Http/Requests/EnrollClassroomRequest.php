<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'code' => 'required|string|exists:classrooms,code',
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
            'code.exists' => 'O código não existe',
        ];
    }
}
