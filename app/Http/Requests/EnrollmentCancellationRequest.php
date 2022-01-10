<?php

namespace App\Http\Requests;

use App\Rules\MemberOfClassroomRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EnrollmentCancellationRequest extends FormRequest
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
            'classroom_id' => [
                'required',
                'numeric',
                Rule::exists('classrooms', 'id')
                    ->where(function($query){
                        $query->where('status', 1);
                    })
                ,
            ],
            'user_id' => [
                'required',
                'numeric',
                'exists:users_classrooms,user_id',
                function ($attribute, $value, $fail) {
                    if ($value === Auth::user()->id) {
                        $fail('Usuário não pode remover a si mesmo da turma.');
                    }
                },
            ],
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
            'classroom_id.required' => 'É obrigatório informar a turma',
            'classroom_id.numeric' => 'O id da turma deve estar em formato numérico',
            'classroom_id.exists' => 'O id da turma não existe ou está associado a uma turma inativa',

            //user_id
            'user_id.required' => 'É obrigatório informar o usuário',
            'user_id.numeric' => 'O id do usuário deve estar em formato numérico',
            'user_id.exists' => 'O usuário informado não está previamente matriculado na turma. Por isso, não é possível removê-lo.',
        ];
    }
}
