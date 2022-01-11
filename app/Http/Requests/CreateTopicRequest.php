<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTopicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:5',
                'max:191'
            ]
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
            //name
            'name.required' => 'É obrigatório preencher o nome do tópico',
            'name.string' => 'O nome do tópico deve estar em formato de texto',
            'name.min' => 'O nome do tópico deve ter no mínimo 5 caracteres',
            'name.max' => 'O nome do tópico deve ter no máximo 5 caracteres',
        ];
    }
}
