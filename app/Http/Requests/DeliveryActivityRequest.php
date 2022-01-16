<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeliveryActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user()->isStudent();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'files.*' => ['sometimes', 'file', 'max:3000', 'mimes:jpeg,png,svg,doc,docx,pdf,xls,xlsx'],
            'activity_id' => ['required', 'numeric', 'exists:users_activities,activity_id'],
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
            //files
            'files.*.file' => 'Apenas arquivos são válidos para este campo',
            'files.*.max' => 'O arquivo deve ter no máximo :max kilobytes',
            'files.*.mimes' => 'O arquivo deve ter uma das seguintes extensões: :values',

            //activity_id
            'activity_id.required' => 'É obrigatório informar a atividade',
            'activity_id.numeric' => 'O id da atividade deve estar em formato numérico',
            'activity_id.exists' => 'Não há usuários atribuídos para entregar esta atividade',
        ];
    }
}
