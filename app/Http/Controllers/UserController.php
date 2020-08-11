<?php App\Http\Controllers;

class UserController extends Controller {
    public function user_request($id) {
        if($id) {
            $user = User::find($id);
            return response(json_encode([
                'status' => 'success',
                'body' => [
                    'user' => $user
                ]
            ]));
        } else {
            return response(json_encode([
                'status' => 'error',
                'errors' => [
                    'You must supply a user id!'
                ]
            ]));
        }
    }
}