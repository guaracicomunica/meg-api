<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateNewRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:800'],
            'disabled' => ['sometimes', 'boolean'],
            'post_type_id' => ['required', 'numeric'],
            'classroom_id' => ['required', 'numeric'],
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
            //post name
            'name.required' => 'O preenchimento do nome da atividade  é necessário',
            'name.string' => 'O nome da atividade  deve estar em formato de texto',
            'name.max' => 'O nome da atividade  deve ter no máximo :max caracteres',

            //post body
            'body.required' => 'O preenchimento do conteúdo da atividade é necessário',
            'body.string' => 'O conteúdo da atividade deve estar em formato de texto',
            'body.max' => 'O conteúdo da atividade deve ter no máximo :max caracteres',
        ];
    }
}
