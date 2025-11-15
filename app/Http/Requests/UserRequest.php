<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => 'required|string',
            'email'            => 'required|unique:users,email,' . $this->user?->id,
            'password'         => $this->isMethod('POST') ? 'required|string|min:6' : 'nullable|string|min:6',
            'confirm_password' => $this->isMethod('POST') ? 'required|same:password' : 'nullable|same:password',
            'roles'            => 'required|array',
            'roles.*'          => 'exists:roles,id',
        ];
    }
}
