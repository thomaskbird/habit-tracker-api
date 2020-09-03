<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Http\Models\Tracker;
use App\Http\Models\TrackerItem;

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

    public function tracker_list(Request $request) {
        $user_id = $this->getUserIdFromToken($request->bearerToken());
        $trackers = Tracker::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        return response(json_encode([
            'status' => 'success',
            'payload' => [
                'trackers' => $trackers
            ]
        ]));
    }

    public function tracker_list_new_format(Request $request) {
        $user_id = $this->getUserIdFromToken($request->bearerToken());

        $trackers = Tracker::with(['tracker_items' => function($query) {
            return $query
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->orderBy('created_at', 'desc');
        }])->with(['tracker_items_chart_data' => function($query) {
            return $query->whereBetween('created_at', [now()->subDays(7), now()])
                ->orderBy('created_at')
                ->get()
                ->groupBy(function($val) {
                    return Carbon::parse($val->created_at)
                            ->format('Y') .'-'. Carbon::parse($val->created_at)->format('m') .'-'. Carbon::parse($val->created_at)->format('d');
                });
        }])->where('user_id', $user_id)
        ->get();

        return response(json_encode([
            'status' => 'success',
            'payload' => [
                'trackers' => $trackers,
            ]
        ]));
    }

    public function tracker_single($id, $range = 7) {
        $tracker = Tracker::with(['tracker_items' => function($query) use ($range) {
            return $query
                ->whereBetween('created_at', [now()->subDays($range), now()])
                ->orderBy('created_at', 'desc');
        }])->find($id);
        $tracker_items = TrackerItem::where('tracker_id', $id)
            ->whereBetween('created_at', [now()->subDays($range), now()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function($val) {
                return Carbon::parse($val->created_at)->format('Y') .'-'. Carbon::parse($val->created_at)->format('m') .'-'. Carbon::parse($val->created_at)->format('d');
            });

        return response(json_encode([
            'status' => 'success',
            'payload' => [
                'tracker' => $tracker,
                'tracker_items' => $tracker_items,
            ]
        ]));
    }

    public function tracker_remove($id) {
        $tracker = Tracker::find($id);
        if($tracker) {
            $tracker->delete();
            return response(json_encode([
                'status' => 'success',
                'payload' => [
                    'message' => 'Tracker item was removed'
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