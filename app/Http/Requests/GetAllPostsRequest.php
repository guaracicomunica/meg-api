<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetAllPostsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isTeacher() || Auth::user()->isStudent();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'classroom_id' => 'required|numeric|exists:classrooms,id'
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
            'classroom_id.required' => 'Deve ser informada qual a turma',
            'classroom_id.numeric' => 'O id da turma deve ter formato numérico',
            'classroom_id.exists' => 'O id da turma não foi encontrado',
        ];
    }
}
