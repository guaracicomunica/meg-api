<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FillReportCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isTeacherOfClassroom($this->request->get('classroom_id'));;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'classroom_id' => ['required', 'numeric', 'exists:classrooms,id'],
            'unit_id' => ['required', 'numeric', 'exists:units,id'],
            'users' => ['required', 'array'],
            'users.*' => ['required', 'numeric', 'exists:users_classrooms,user_id'],
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
            //classroom_id
            'classroom_id.required' => 'Deve ser informada uma turma para preenchimento de boletim',
            'classroom_id.*.numeric' => 'O id da turma deve ser numérico',
            'classroom_id.*.exists' => 'A turma é inexistente',

            //unit_id
            'unit_id.required' => 'Deve ser informada uma unidade (bimestre) para preenchimento de boletim',
            'unit_id.*.numeric' => 'O id da unidade deve ser numérico',
            'unit_id.*.exists' => 'O sistema não suporta essa unidade',

            //users
            'users.required' => 'Deve ser informado ao menos um aluno da turma para preenchimento de boletim',
            'users.array' => 'Espera-se os alunos em formato de array',
            'users.*.numeric' => 'O id dos alunos deve ser numérico',
            'users.*.exists' => 'Aluno não está matriculado na turma',
        ];
    }
}
