<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class AccountController extends Controller
{
    public function changePassword(Request $request, $user_id){
        $changePassword = User::find($user_id);
        $changePassword->password = Hash::make($request->input('new_password'));
        $changePassword->save();
    }
    
    public function changeDetails(Request $request, $user_id){
        $changeDetails = User::find($user_id);
        $changeDetails->first_name = $request->input('first_name');
        $changeDetails->middle_name = $request->input('middle_name');
        $changeDetails->last_name = $request->input('last_name');
        $changeDetails->extension_name = $request->input('extension_name');
        $changeDetails->birth_date = $request->input('birth_date');
        $changeDetails->gender = $request->input('gender');
        $changeDetails->save();
    }
    
    public function changePhoto(Request $request) {

        $user = User::find($request->input('user_id'));
        $user->photo_src = time().'.'.$request->file('photo_img')->getClientOriginalExtension();
        $request->file('photo_img')->move(public_path('../../pps-frontend/src/assets/storage/images/profiles/'), $user->photo_src);

        $user->save();
    }
}
