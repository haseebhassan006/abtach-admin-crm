<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;
use App\Interfaces\UsersInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $userRespository;
    public function __construct(UsersInterface $userRespository)
    {
        $this->userRespository = $userRespository;
    }
    public function index(Request $request)
    {
        
        $data = $this->userRespository->index($request->all());
        if (view()->exists('admin.users.index')) {
            return view('admin.users.index', $data);
        } else {
            abort(404);
        }
    }
    public function create()
    {
        
        $data = $this->userRespository->create();
        if (view()->exists('admin.users.create')) {
            return view('admin.users.create', $data);
        } else {
            abort(404);
        }
    }
    public function store(Request $request)
    {

        
        $array = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|max:50',
            'phone' => 'required',
            'role_id' => 'sometimes|nullable|array|exists:roles,id',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',

        ], [
            'name.required' => 'Please Insert Name',
            'name.max' => 'First Name Field must not be greater than 50 digits',

           
            // 'brand_id.required' => 'Please Select Brand',
            // 'team_lead.required' => 'Please Select Team Lead ',
            // 'sales_manager.required' => 'Please Select Sales Maneger ',
            // 'country_id.required' => 'Please Select Country ',

            'password.required' => 'Please insert Password ',
            'password.max' => 'Password Field must not be greater than 50 digits',
            
        ]);
        // $validate = $request->validated();
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $create = $this->userRespository->store($request->all());
            if ($create == true) {
                return redirect()->route('admin.users.index')->with('success', 'User has been Created');
            }
            return redirect()->route('admin.users.create')->with('error', 'User not Created');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function show($id)
    {
        $this->authorize("UsersView");
        $data = $this->userRespository->show($id);
        if ($data == false) {
            return redirect()->route('admin.users.index')->with('error', 'User not found or deleted!');
        }
        if (view()->exists('admin.users.show')) {
            return view('admin.users.show', $data);
        } else {
            abort(404);
        }
    }
    public function edit($id)
    {
        $this->authorize("UsersEdit");

        $data = $this->userRespository->edit($id);
        if ($data == false) {
            return redirect()->route('admin.users.index')->with('error', 'User not found or deleted!');
        }
        if (view()->exists('admin.users.edit')) {
            return view('admin.users.edit', $data);
        } else {
            abort(404);
        }
    }
    public function update(Request  $request, $id)
    {
        $this->authorize("UsersEdit");
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'extension' => 'required|string',
            'country_id' => 'required|integer',
            // 'phone' => 'required',
            'role_id' => 'sometimes|nullable|array|exists:roles,id',
            'password' => 'sometimes|confirmed',
            'email' => 'required|email|unique:users,email,' . $id,

        ], [
            'name.required' => 'Please Insert First Name',
            'name.string' => 'First Name must be in alphabets format like (John Doe)',
            'name.max' => 'First Name Field must not be greater than 50 digits',

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
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $update = $this->userRespository->update($request->all(), $id);
            if ($update == true) {
                return redirect()->back()->with('success', 'User has been Updated');
            }
            return redirect()->back()->with('error', 'User not updated, Try again!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
    public function destroy($id)
    {
        $this->authorize("UsersDelete");
        try {
            $delete  = $this->userRespository->delete($id);
            if ($delete == true) {
                return  redirect()->route('admin.users.index')->with('success', 'User has been deleted!');
            }
            return redirect()->route('admin.users.index')->with('error', 'User not deleted!');
        } catch (Exception $e) {
            return redirect()->route('admin.users.index')->with('error', $e->getMessage());
        }
    }
    public function getRoles(Request $request)
    {
        $getRoles =  $this->userRespository->getRoles($request->user_id);
        if ($getRoles == false) {
            return response()->json(['success' => false, 'message' => "User does not exist"]);
        }
        return response()->json(['success' => true, 'roles' => $getRoles, 'message' => 'Roles Fetched Successfuly!']);
    }
    public function direct_permission($id)
    {
        $this->authorize("UsersDirectpermissionCreate");
        $data = $this->userRespository->directPermission($id);
        if ($data != true) {
            return redirect()->route('admin.users.index')->with('error', 'User Not FOund!');
        }
        return view('admin.users.direct', $data);
    }
    public function storeDirectPermission(UserRequest $request)
    {
        $this->authorize("UsersDirectpermissionCreate");
        $validate = $request->validated();
        try {
            $store = $this->userRespository->storeDirectPermission($request->all());
            if ($store != true) {
                return redirect()->back()->with('error', 'Failed to assigned permissions directly, Try again!');
            }
            return redirect()->back()->with('success', 'Permission Assigned to User!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function deleteDirectPermission($permission_id, $user_id)
    {
        $this->authorize("UsersDirectpermissionDelete");
        $array = [
            'user_id' => $user_id,
            'permission_id' => $permission_id,
        ];
        $delete = $this->userRespository->deleteDirectPermission($array);
        if ($delete == true) {
            return redirect()->back()->with('success', 'Permission removed from User!');
        }
        return redirect()->back()->with('error', 'User Not FOund!');
    }
    public function manager()
    {

        $this->authorize("UsersManager");
        return "Manager Portal for User";
    }


}
