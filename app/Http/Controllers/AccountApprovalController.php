<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountApproval;
use App\User;

class AccountApprovalController extends Controller
{
    public function index(){
        $accounts = AccountApproval::getAccount();
        return response()->json($accounts);
    }

    public function update(Request $request, $user_id){
        $approvedAccount = User::find($user_id);
        $approvedAccount->is_approved = $request->input('is_approved');
        $approvedAccount->approved_by = $request->input('approved_by');
        $approvedAccount->save();
    }
}
