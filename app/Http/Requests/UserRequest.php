<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
    public function rules()
    {

        if (request()->routeIs('users.store')) {
            return $this->validateForStore();
        } elseif (request()->routeIs('users.update')) {
            return $this->validateForUpdate();
        } else {
            // for uses.storeDirectPermission
            // return $this->validateForStoreDirectPermission();
        }
    }
    public function validateForStore()
    {
        $array =  [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|max:50',
            'role_id' => 'sometimes|nullable|array|exists:roles,id',
        ];
        return $array;
    }
    public function validateForUpdate()
    {
        $model_id = "";
        foreach (request()->route()->parameters as $index => $value) {
            $model_id = $value;
        }
        $user = User::find($model_id);
        $array =  [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|max:50',
            'role_id' => 'sometimes|nullable|array|exists:roles,id',
        ];
        $array['email'] = [
            'required', 'email', Rule::unique('users')->ignore($user->email),
        ];
        return $array;
    }
  
    public function messages()
    {
        return [
            'name.required' => 'Please Insert First Name',
            'name.string' => 'First Name must be in alphabets format like (John Doe)',
            'name.max' => 'First Name Field must not be greater than 50 digits',

            // 'last_name.required' => 'Please Insert Last Name',
            // 'last_name.string' => 'Last Name must be in alphabets format like (John Doe)',
            // 'last_name.max' => 'Last Name Field must not be greater than 50 digits',

            // 'extension.required' => 'Please Insert Extension',
            // 'brand_id.required' => 'Please Select Brand',
            // 'team_lead.required' => 'Please Select Team Lead ',
            // 'sales_manager.required' => 'Please Select Sales Maneger ',
            // 'country_id.required' => 'Please Select Country ',

            'password.required' => 'Please insert Password ',
            'password.max' => 'Password Field must not be greater than 50 digits',
            // 'phone.required' => 'Please insert phone number',
            // 'phone.max' => 'Phone number must not be greater than 11 digits',
            // 'phone.min' => 'Phone number must contain atleast 8 digits',
        ];
    }
}
