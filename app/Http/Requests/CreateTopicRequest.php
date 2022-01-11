<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
        return
            Auth::user()->hasVerifiedEmail()
            && Auth::user()->isTeacherOfClassroom($this->request->get('classroom_id'));
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
                'max:191',
              /* TO DO: validação de name único em caso de mesmo classroom_id
                Rule::exists('topics', 'name')
                    ->where(function($query)
                {
                    $query->where('classroom_id',  $this->request->get('classroom_id'));
                })*/
            ],
            'classroom_id' => [
                'required',
                'numeric',
                'exists:classrooms,id'
            ],
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

            //classroom_id
            'classroom_id.required' => 'É obrigatório informar a turma à qual o tópico está associado',
            'classroom_id.string' => 'O id da turma deve estar em formato numérico',
            'classroom_id.exsits' => 'Não há turma existente com o id informado',
        ];
    }
}
