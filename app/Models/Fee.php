<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fine',
    ];

    public function student() {
        return $this->belongsTo('App\Models\Student');
    }
}
