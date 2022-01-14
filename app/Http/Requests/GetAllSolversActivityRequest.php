<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetAllSolversActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isTeacher();
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
        ];
    }
}
