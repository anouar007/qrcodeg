<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user('web');

        if (! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasPermission('create_users') || $user->hasPermission('manage_users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:200',
            'first_name' => 'required_without:name|nullable|string|max:100',
            'last_name' => 'required_without:name|nullable|string|max:100',
            'username' => 'nullable|string|max:50|alpha_dash|unique:users,username',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|string|in:active,suspended,inactive',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('name') && (! $this->filled('first_name') || ! $this->filled('last_name'))) {
            $parts = explode(' ', trim($this->input('name')), 2);
            $this->merge([
                'first_name' => $parts[0] ?? '',
                'last_name' => $parts[1] ?? '',
            ]);
        }

        if (! $this->filled('username') && $this->filled('email')) {
            $this->merge([
                'username' => explode('@', $this->input('email'))[0],
            ]);
        }
    }
}
