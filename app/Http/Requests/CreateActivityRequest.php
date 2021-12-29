<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateActivityRequest extends FormRequest
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
            'deadline' => ['required', 'date_format:Y-m-d H:i:s' ],
            'points' => ['required','numeric', 'between:1,100'],
            'coins' => ['required','numeric'],
            'xp' => ['required','numeric'],
            'disabled' => ['required', 'boolean'],
            'classroom_id' => ['required', 'numeric', 'exists:classrooms,id'],
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

            //post deadline
            'deadline.dateformat' => 'O prazo deve ser do tipo data e hora',
            'deadline.required' => 'O preenchimento do prazo da atividade é necessário',


            //post points
            'points.required' => 'O preenchimento dos pontos da atividade é necessário',
            'points.numeric' => 'A indicação dos pontos da atividade deve estar em formato númerico',
            'points.between' => 'A indicação dos pontos da atividade deve estar entre :min e :max',

            //post coins
            'coins.required' => 'O preenchimento dos pontos da atividade é necessário',
            'coins.numeric' => 'A indicação dos pontos da atividade deve estar no formato númerico',

            //post xp
            'xp.required' => 'O preenchimento da experience (xp) da atividade  é necessário',
            'xp.numeric' => 'A informação de xp deve estar no formato númerico',

            //classroom_id
            'classroom_id.required' => 'O preenchimento da turma é necessário',
            'classroom_id.numeric' => 'A id da turma deve estar no formato númerico',
            'classroom_id.exists' => 'Não foi encontrada a turma'

        ];
    }
}
