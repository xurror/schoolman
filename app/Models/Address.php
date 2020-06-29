<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model {

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
