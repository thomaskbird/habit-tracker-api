<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Http\Models\Tracker;

class TrackerController extends Controller {
    public function tracker_create(Request $request) {
        $input = $request->except('_token');

        $validator = Validator::make($input, [
            'name' => 'required|unique:trackers',
            'type' => 'required',
        ]);

        if($validator->fails()) {
            return response(json_encode([
                'status' => 'error',
                'errors' => $validator->errors()
            ]));
        } else {
            $input['user_id'] = 1;
            $tracker = Tracker::create($input);

            if($tracker) {
                return response(json_encode([
                    'status' => 'success',
                    'payload' => [
                        'tracker' => $tracker
                    ]
                ]));
            } else {
                return response(json_encode([
                    'status' => 'error',
                    'errors' => [
                        'Uh oh, something went wrong please try again!'
                    ]
                ]));
            }
        }
    }

    public function tracker_list() {
        $trackers = Tracker::all();
        return response(json_encode([
            'status' => 'success',
            'payload' => [
                'trackers' => $trackers
            ]
        ]));
    }
}