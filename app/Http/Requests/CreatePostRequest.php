<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePostRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:800'],
            'disabled' => ['sometimes', 'boolean'],
            'is_private' => ['required', 'boolean'],
            'classroom_id' => ['required', 'numeric', 'exists:classrooms,id'],
            'attachments.*' => ['sometimes', 'file', 'max:3000', 'mimes:pdf,doc,docx'],
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
            'name.required' => 'O preenchimento do nome do post  é necessário',
            'name.string' => 'O nome do post  deve estar em formato de texto',
            'name.max' => 'O nome do post  deve ter no máximo :max caracteres',

            //post body
            'body.required' => 'O preenchimento do conteúdo do post é necessário',
            'body.string' => 'O conteúdo do post deve estar em formato de texto',
            'body.max' => 'O conteúdo do post deve ter no máximo :max caracteres',

            //post disabled
            'disabled.boolean' => 'O formato do status deve ser booleano',

            // post is private
            'is_private.required' => 'Informe se o post é privado',
            'is_private.boolean' => 'O formato do nível de acesso (is_private) deve ser booleano',

            //classroom
            'classroom_id.required' => 'O id da turma é obrigatório',
            'classroom_id.numeric' => 'O id da turma deve estar em formato numérico',
            'classroom_id.exists' => 'A turma não foi encontrada',

            //files
            'attachments.*.max'  => 'Os arquivos precisam ter tamanho máximo de 3000KB',
            'attachments.*.file'  => 'Os arquivos precisam ser do tipo docx, doc ou pdf',
        ];
    }
}
