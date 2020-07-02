<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model {
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address_line_1', 'address_line_2', 'region', 'city',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
