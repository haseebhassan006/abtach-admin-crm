<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $route = getCurrentRoute();
        if ($route == "admin.roles.store") {
            return $this->validateStoreRequest();
        }
        if ($route == "admin.roles.update") {
            return $this->validateUpdateRequest();
        }
    }
    public function validateStoreRequest()
    {
        $array =  [
            'name' => 'required|unique:roles,name',
            'user_id' => 'sometimes|nullable|array',
            'user_id.*' => 'numeric|exists:users,id',
        ];
        return $array;
    }
    public function validateUpdateRequest()
    {
        $model_id = "";
        foreach (request()->route()->parameters as $index => $value) {
            $model_id = $value;
        } 
        $role = Role::find($model_id->id); 
        $array = [
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,id',
        ];
        $array['name'] = [
             'required','max:190',  Rule::unique('roles')->ignore($role->id),
        ];
        return $array;
    }
    public function messages()
    {
        return [
            'name.required' => 'Please Insert Name',
            'name.string' => 'Name must be in alphabets format like (John Doe)',
            'name.unique' => 'Another role already exists with this name, Please enter a unique Role Name',
            'user_id.required' => 'At least 1 user must be selected',
            'user_id.numeric' => 'Invalid format of roles',
            'user_id.exists' => 'Role you are trying to assign to the user does not exist',
        ];
    }
}
