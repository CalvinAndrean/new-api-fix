<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $infoLogin = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($infoLogin)){
            $user = Auth::user();
            $token = $user->createToken('Authentication Token')->accessToken;

            return response([
                'message' => 'Authenticated Logged In',
                'user' => $user,
                'token_type' => 'Bearer',
                'access_token' => $token
            ], 200);
        } else{
            return response([
                'message' => 'Login Gagal',
                'data' => $infoLogin
            ], 400);
        }
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Logged Out'
        ]);
    }

    public function getUserById($id_user){
        $user = User::find($id_user);

        return response([
            'data' => $user
        ], 200);
    }

    public function updatePassword(Request $request, $id_user){
        // panggil api updatePassword (bawa request nya User isinya ada email, password, password_new) sama id_user yang masi login
        $User = User::where('id_user', $id_user)->first();

        // return response([
        //     'data' => $User
        // ], 200);

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'password' => 'required'
        ]);

        if($validate->fails()){
            return response([
                'message' => $validate->errors(),
                'data' => $request->password
            ], 400);
        }

        $infoLogin = [
            'email' => $request->email,
            'password' => $request->password
        ];

        // $request bakal bawa email sama password old
        if(Auth::attempt($infoLogin) && $User->email == $request->email){

            if(str_contains($id_user, '.')){
                // ganti password Instructor

                $Instructor = Instructor::where('id_instructor', $id_user)->first();
                $Instructor->password = Hash::make($updateData['password_new']);
                $User->password = Hash::make($updateData['password_new']);
                $Instructor->save();
                $User->save();

                return response([
                    'data' => "Success update password instructor"
                ], 200);
            } else{
                // ganti password Pegawai

                $Pegawai = Pegawai::where('id_pegawai', $id_user)->first();
                $Pegawai->password = Hash::make($updateData['password_new']);
                $User->password = Hash::make($updateData['password_new']);
                $Pegawai->save();
                $User->save();

                return response([
                    'data' => "Success update password pegawai"
                ], 200);
            }

        }

        return response([
            'message' => 'Update Password Failed',
            'data' => null
        ], 400);
    }
}
