<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateCommentRequest extends FormRequest
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
            'body' => ['required', 'string', 'max:150'],
            'post_id' => ['required', 'numeric'],
            'user_id' => ['required', 'numeric'],
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
            //post body
            'body.required' => 'O preenchimento do conteúdo do comentário é necessário',
            'body.string' => 'O conteúdo do comentário deve estar em formato de texto',
            'body.max' => 'O conteúdo da atividade deve ter no máximo :max caracteres',
        ];
    }
}
