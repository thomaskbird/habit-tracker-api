<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Tracker extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'description'
    ];
}