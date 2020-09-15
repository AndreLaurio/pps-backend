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
}
