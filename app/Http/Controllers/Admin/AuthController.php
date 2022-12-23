<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return view('admin.auth.login');
    }

    public function authenticate(Request $request)
    {

         
        $validator = $this->validate($request, [
            'email' => 'required|email|max:50',
            'password' => 'required|max:50',
        ]);
       
        try {
            if (!empty($request->email)) {
               
                $user = User::where('email', $request->email)->first();
                if (!empty($user)) {
                
                        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
                        $currentUser = Auth::user();
                        return redirect()->route('admin.dashboard')->with('welcome', 'Welcome to Dashboard!');
            
                }
                return redirect()->route('admin.login')->with('error', 'Invalid Credentials');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.login')->with('error', $e->getMessage());
        }
    }
}
