<?php

namespace App\Http\Requests;

use App\Models\Classroom;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateActivityRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:100'],
            'body' => ['sometimes', 'string', 'max:800'],
            'deadline' => ['sometimes', 'date_format:Y-m-d H:i:s', 'after:'.date(DATE_ATOM, time())],
            'points' => ['sometimes','numeric', 'between:1,100'],
            'coins' => ['sometimes','numeric'],
            'xp' => ['sometimes','numeric'],
            'disabled' => ['sometimes', 'boolean'],
            'classroom_id' => ['sometimes', 'numeric', 'exists:classrooms,id'],
            'topic_id' => ['sometimes', 'numeric', 'exists:topics,id'],
            'unit_id' => ['sometimes', 'numeric', 'exists:units,id'],
            'attachments.*' => ['sometimes', 'file', 'max:3000', 'mimes:jpeg,png,svg,doc,docx,pdf,xls,xlsx'],
            'links' => ['sometimes', 'array'],
            'links.*' => ['string'],
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
            'name.string' => 'O nome da atividade  deve estar em formato de texto',
            'name.max' => 'O nome da atividade  deve ter no máximo :max caracteres',

            //post body
            'body.string' => 'O conteúdo da atividade deve estar em formato de texto',
            'body.max' => 'O conteúdo da atividade deve ter no máximo :max caracteres',

            //post deadline
            'deadline.dateformat' => 'O prazo deve ser do tipo data e hora',
            'deadline.required' => 'O preenchimento do prazo da atividade é obrigatório',
            'deadline.after' => 'O prazo de entrega da atividade não pode anteceder a data de cadastro',

            //post points
            'points.numeric' => 'A indicação dos pontos da atividade deve estar em formato númerico',
            'points.between' => 'A indicação dos pontos da atividade deve estar entre :min e :max',

            //post coins
            'coins.numeric' => 'A indicação dos pontos da atividade deve estar no formato númerico',

            //post xp
            'xp.numeric' => 'A informação de xp deve estar no formato númerico',

            //topic_id
            'topic_id.numeric' => 'O id do tópico deve estar em formato numérico',
            'topic_id.exists' => 'O tópico não foi encontrado',

            //unit_id
            'unit_id.numeric' => 'O id da unidade deve estar em formato numérico',
            'unit_id.exists' => 'A unidade não foi encontrada (existem até 4)',

            //files
            'attachments.*.max'  => 'Os arquivos precisam ter tamanho máximo de 3000KB',
            'attachments.*.file'  => 'Os arquivos precisam ser do tipo docx, doc ou pdf',
            'attachments.*.mimes'  => 'Os arquivos precisam ser do tipo docx, doc ou pdf',

            //links
            'links.array' => 'Espera-se os links em formato de array',
            'links.*.string' => 'Espera-se links em formato de texto',
        ];
    }
}
