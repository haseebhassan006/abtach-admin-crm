<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $route = getCurrentRoute();
        if ($route == "admin.permissions.store") {
            return $this->validateStoreRequest();
        }
        if ($route == "admin.permissions.update") {
            return $this->validateUpdateRequest();
        }
    }

    public function validateStoreRequest()
    {
        $array = [
            'name' => 'required|max:190|unique:permissions,name',
            'role_id' => 'sometimes|array',
            'role_id.*' => 'numeric|exists:roles,id'
        ];
        return $array;
    }
}
