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
            'classroom_id.string' => 'O código da turma deve estar em formato de texto',
            'classroom_id.exists' => 'O código não existe',
        ];
    }
}
