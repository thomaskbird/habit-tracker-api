<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller {
    public function login_request(Request $request) {
        $input = $request->except('_token');
        print_r($input);

        $validated = $request->validate([
            'email' => 'required|unique:users',
            'password' => 'required'
        ]);

        if($validated->fails()) {
            return response(json_encode([
                'status' => 'error',
                'errors' => $validated->errors()
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