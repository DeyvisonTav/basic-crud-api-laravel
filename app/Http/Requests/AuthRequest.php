<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ];

        // Adiciona regras extras para registro
        if ($this->routeIs('register')) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|string|email|max:255|unique:users';
        }

        return $rules;
    }

    public function messages(): array
    {  
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'password.required' => 'O campo senha é obrigatório.',
            'email.unique' => 'O email já está em uso.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'name.string' => 'O nome deve ser uma string.',
            'email.string' => 'O email deve ser uma string.',
            'password.string' => 'A senha deve ser uma string.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            'email.email' => 'O formato do email é inválido.',
        ];
    }
    
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Os dados fornecidos são inválidos.',
            'errors' => $validator->errors(),
        ], 422));
    }
}