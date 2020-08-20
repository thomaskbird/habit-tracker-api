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
}