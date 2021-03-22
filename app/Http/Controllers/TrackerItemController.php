<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Models\TrackerItem;

class TrackerItemController extends Controller {

    public function tracker_item_create(Request $request, $tracker_id) {
        $input = $request->all();
        $tracker_data = ['tracker_id' => $tracker_id];

        if(isset($input['note'])) {
            $tracker_data['note'] = $input['note'];
        }

        $tracker_item = TrackerItem::create($tracker_data);
        return response(json_encode([
            'status' => 'success',
            'payload' => [
                'tracker_item' => $tracker_item
            ]
        ]));
    }

    public function tracker_item_remove($tracker_item_id) {
        $tracker_item_deleted = TrackerItem::find($tracker_item_id);
        $tracker_item_deleted->delete();

        return response(json_encode([
            'status' => 'success',
            'payload' => [
                'message' => 'Tracker item was removed'
            ]
        ]));
    }

    public function tracker_item_single($tracker_item_id) {
        $tracker_item = TrackerItem::find($tracker_item_id);

        return response(json_encode([
            'status' => 'success',
            'payload' => [
                'tracker_item' => $tracker_item
            ]
        ]));
    }
}