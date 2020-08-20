<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Models\TrackerItem;

class TrackerItemController extends Controller {

    public function tracker_item_create($tracker_id) {
        $tracker_item = TrackerItem::create(['tracker_id' => $tracker_id]);
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
}