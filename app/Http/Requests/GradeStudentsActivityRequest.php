<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GradeStudentsActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isStudent();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'activity_id' => ['required', 'numeric', 'exists:activities,id'],
            'users.*.id' => ['required', 'numeric', 'exists:users_activities,user_id'],
            'users.*.grade' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            //activity_id
            'activity_id.required' => 'Deve ser informada qual a atividade',
            'activity_id.numeric' => 'O id da atividade deve ter formato numérico',
            'activity_id.exists' => 'O id da atividade não foi encontrado',

            //users
            'users.*.id.required' => 'Deve ser informado qual o usuário para atribuição de nota',
            'users.*.id.required' => 'O id do usuário deve ter formato numérico',
            'users.*.id.exists' => 'O id do usuário não foi encontrado',
        ];
    }
}
