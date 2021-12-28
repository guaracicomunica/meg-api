<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateClassroomRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:191'],
            'nickname' => ['required', 'string', 'max:191'],
            'file' => ['sometimes', 'file', 'max:500', 'mimes:jpeg,png,svg'],
            'partners.*' => ['sometimes','email'],
            'levels' => ['required', 'array'],
            'levels.*.name' => ['required', 'string'],
            'levels.*.xp' => ['required','numeric', 'min:0'],
            'skills' => ['sometimes', 'array'],
            'skills.*.coins' => ['required','numeric', 'min:0'],
            'skills.*.name' => ['required', 'string'],
            'is_draft' => ['required', 'boolean']
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
            //classroom name
            'name.required' => 'O preenchimento do nome da turma é necessário',
            'name.string' => 'O nome da turma deve estar em formato de texto',
            'name.max' => 'O nome da turma deve ter no máximo :max caracteres',

            //classroom nickname
            'nickname.required' => 'O preenchimento do nome da sala é necessário',
            'nickname.string' => 'O nome nome da sala deve estar em formato de texto',
            'nickname.max' => 'O nome nome da sala deve ter no máximo :max caracteres',

            //email of partners
            'partners.*.email' => 'O email não está no formato padrão, corrija-o e tente novamente',

            //levels
            'levels.required' => 'Deve ser informado ao menos um nível para a turma',
            'levels.array' => 'Espera-se os níveis em formato de array',
            'levels.*.xp.min' => 'O xp não deve ser negativo',
            'levels.*.xp.required' => 'O preenchimento do xp é necessário',
            'levels.*.name.required' => 'O preenchimento do nome do nível é necessário',

            //skills
            'skills.array' => 'Espera-se as habilidades em formato de array',
            'skills.*.coins.min' => 'A quantidade moedas não podem ser negativa',
            'skills.*.name.required' => 'O preenchimento do nome da habilidade é necessário',

            //banner file
            'file.file' => 'Apenas arquivos são válidos para este campo',
            'file.max' => 'O arquivo deve ter no máximo :max kilobytes',
            'file.mimes' => 'O arquivo deve ter uma das seguintes extensões: :values',

            //is_draft
            'is_draft.required' => 'Informe se cadastro de turma é rascunho ou não',
            'is_draft.boolean' => 'O campo deve ser booleano'
        ];
    }
}
