<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    /**
     * chart data interface
     *
     *  id - YYYY-MM-DD
     *  label - MM/DD
     *  count - number
     */

    public function tracker_list_new_format(Request $request, $range = 7) {
        $user_id = $this->getUserIdFromToken($request->bearerToken());
        $tracker_return = [];

        $trackers = Tracker::with(['tracker_items' => function($query) use ($range) {
            return $query
                ->whereBetween('created_at', [now()->subDays($range), now()])
                ->orderBy('created_at', 'desc');
        }])->where('user_id', $user_id)
        ->get();

        foreach($trackers as $tracker) {
            $formatted_data = $tracker->toArray();
            $chart_data = [];

            for($i = 0; $i < $range; $i++) {
                if($i === 0) {
                    $today = now()->toDateString();
                    $chart_data['id'] = $today;
                    $chart_data['label'] = now()->format('m/d');
                    $chart_data['count'] = count($this->findMatching($today, $tracker->tracker_items));
                } else {
                    $past = now()->subDays($i);
                    $chart_data['id'] = $past;
                    $chart_data['label'] = $past->format('m/d');
                    $chart_data['count'] = count($this->findMatching($past, $tracker->tracker_items));
                }
            }

            $formatted_data['chart_data'] = $chart_data;

            array_push($tracker_return, $formatted_data);
        }



        print_r($tracker_return);exit;

        return response(json_encode([
            'status' => 'success',
            'payload' => [
                'trackers' => $trackers,
            ]
        ]));
    }

    private function findMatching($timestamp, $items) {
        $found = [];

        foreach($items as $item) {
            $item_timestamp = Carbon::parse($item->created_at);

            if($timestamp === $item_timestamp->format('Y-m-d')) {
                array_push($found, $item);
            }
        }

        return $found;
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