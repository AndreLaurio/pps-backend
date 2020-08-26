<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AccountApproval extends Model
{
    public function scopeGetAccount(){
        $query = DB::table('users')
        ->where('is_approved', 0)
        ->select('id','first_name','last_name','extension_name','email')
        ->get();

        return $query;
    }
}
