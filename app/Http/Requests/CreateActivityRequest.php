<?php

namespace App\Http\Requests;

use App\Models\Classroom;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isTeacherOfClassroom($this->request->get('classroom_id'));
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
            'deadline' => ['required', 'date_format:Y-m-d H:i:s', 'after:'.date(DATE_ATOM, time())],
            'points' => ['required','numeric', 'between:1,100'],
            'coins' => ['required','numeric'],
            'xp' => ['required','numeric'],
            'disabled' => ['required', 'boolean'],
            'classroom_id' => ['required', 'numeric', 'exists:classrooms,id'],
            'topic_id' => ['required', 'numeric', 'exists:topics,id'],
            'unit_id' => ['required', 'numeric', 'exists:units,id'],
            'attachments.*' => ['sometimes', 'file', 'max:3000', 'mimes:jpeg,png,svg,doc,docx,pdf,xls,xlsx'],
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
            'deadline.required' => 'O preenchimento do prazo da atividade é obrigatório',
            'deadline.after' => 'O prazo de entrega da atividade não pode anteceder a data de cadastro',

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
            'classroom_id.exists' => 'Não foi encontrada a turma',

            //topic_id
            'topic_id.required' => 'O id do tópico é obrigatório',
            'topic_id.numeric' => 'O id do tópico deve estar em formato numérico',
            'topic_id.exists' => 'O tópico não foi encontrado',

            //unit_id
            'unit_id.required' => 'É obrigatório informar à qual unidade esta atividade será vinculada',
            'unit_id.numeric' => 'O id da unidade deve estar em formato numérico',
            'unit_id.exists' => 'A unidade não foi encontrada (existem até 4)',

            //files
            'attachments.*.max'  => 'Os arquivos precisam ter tamanho máximo de 3000KB',
            'attachments.*.file'  => 'Os arquivos precisam ser do tipo docx, doc ou pdf',
            'attachments.*.mimes'  => 'Os arquivos precisam ser do tipo docx, doc ou pdf',
        ];
    }
}
