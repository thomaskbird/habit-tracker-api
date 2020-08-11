<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller {
    public function login_request(Request $request) {
        $input = $request->except('_token');

        $validator = Validator::make($input, [
            'email' => 'required|unique:users',
            'password' => 'required'
        ]);

        if($validator->fails()) {
            return response(json_encode([
                'status' => 'error',
                'errors' => $validator->errors()
            ]));
        } else {
            if(Auth::attempt($input)) {
                return response(json_encode([
                    'status' => 'success',
                    'payload' => [
                        'user' => Auth::user()
                    ]
                ]));
            } else {
                return response(json_encode([
                    'status' => 'error',
                    'errors' => ['The email or password did not match records, please try again!']
                ]));
            }
        }
    }
}