<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nature_of_job', 'basic_pay', 'doj',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function department() {
        return $this->belongsTo('App\Models\Department');
    }

}
