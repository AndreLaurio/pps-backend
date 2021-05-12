<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DB;
use Log;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $users = DB::table('users AS u')
                ->leftJoin('user_types AS ut', 'ut.user_type_id', 'u.user_type_id')
                ->where([
                    ['user_id', '!=', $request->user()->user_id]
                ])
                ->orderByRaw('u.user_type_id ASC, last_name ASC, first_name ASC, middle_name ASC')
                ->paginate(1000);

            return response()->json([
                'code' => 200,
                'users' => $users,
                'message' => 'Getting users is successful'
            ]);
        }
        catch(QueryException $e) {
            Log::debug($e);
            return response()->json([], 500);
        }
    }

    public function delete(Request $request, $userId)
    {
        try {

            DB::beginTransaction();

            // Check if has exams
            $hasExams = DB::table('examinee_exams')
                ->where('user_id', $userId)
                ->exists();

            if($hasExams) {
                return response()->json([
                    'code' => 200,
                    'message' => 'The user has exams taking/taken.'
                ]);
            }

            // Delte
            DB::table('users')
                ->where('user_id', $userId)
                ->delete();

            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'Deleting user is successful.'
            ]);
        }
        catch(QueryException $e) {
            DB::rollback();
            Log::debug($e);
            return response()->json([], 500);
        }
    }

    public function update(Request $request)
    {
        try {

            DB::beginTransaction();

            // Check email duplication
            $hasDuplicateEmail = DB::table('users')
                ->where([
                    ['user_id', '!=', $request->input('user_id')],
                    ['email', '=', $request->input('email')]
                ])
                ->exists();

            if($hasDuplicateEmail) {
                return response()->json([
                    'code' => 409,
                    'message' => 'The email already exists.'
                ], 409);
            }

            // Delte
            DB::table('users')
                ->where('user_id', $request->input('user_id'))
                ->update([
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'email' => $request->input('email'),
                    'user_type_id' => $request->input('user_type_id')
                ]);

            // Update password
            if($request->input('password')) {
                
                DB::table('users')
                    ->where('user_id', $request->input('user_id'))
                    ->update([
                        'password' => Hash::make($request->input('password'))
                    ]);
            }

            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'Updating user is successful.'
            ], 200);
        }
        catch(QueryException $e) {
            DB::rollback();
            Log::debug($e);
            return response()->json([], 500);
        }
    }
}
