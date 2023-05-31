<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.  
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['data' => $users] , 200);
    }
 
    /**      
     *    
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
    
        $this->validate($request, $rules);
    
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;
    
        $user = User::create($data);
    
        return response()->json(['data' => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json(['data' => $user], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = user::findOrFail($id);
        $rules = [
            'email'    => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin'    => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER, 
        ];

        $this->validate($request , $rules);

        if($request->has('name')){
            $user->name =  $request->name;
        } // end if
        
        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        } // end if

        if($request->has('password')){
            $user->password = bcrypt($request->password); 
        } // end if

        if($request->has('admin') ){
            if(!$user->isVerified()){
                return response()->json(['error' => 'Only verified users can modify the admin field' , 'code' => 409] , 409);
            }

            $user->admin = $request->admin;
        } // end if

        if(!$user->isDirty()){
            return response()->json(['error' => 'You need to specify a different value to update' , 'code' => 422] , 422);
        }

        $user->save();
        return response()->json(['data' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return response()->json(['data' => $user], 200);
    }
}
