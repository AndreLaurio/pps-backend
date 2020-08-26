<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountApproval;

class AccountApprovalController extends Controller
{
    public function index(){
        $accounts = AccountApproval::getAccount();
        return response()->json($accounts);
    }

    public function update(Request $request, $id ){
        $approvedAccount = User::find($id);
        $approvedAccount->is_approved = $request->input('approved');
        $approvedAccount->save();
    }
}
