<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TrackerItem extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tracker_id',
        'amount',
    ];
}