<?php

namespace App\Http\Requests\Admin;

use App\Enums\AppRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('user')
            ? $this->user()->can('update', $this->route('user'))
            : $this->user()->can('create', User::class);
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['username' => Str::lower(trim((string) $this->input('username')))]);
    }

    public function rules(): array
    {
        $roles = $this->user()->hasRole('superadmin')
            ? AppRole::values()
            : array_values(array_diff(AppRole::values(), ['superadmin']));

        return [
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'regex:/\A[a-z0-9][a-z0-9._-]*\z/', Rule::unique('users')->ignore($this->route('user'))],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'string', 'distinct', Rule::in($roles)],
        ];
    }
}
