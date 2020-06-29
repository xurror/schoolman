<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'title', 'credits',
    ];

    public function department() {
        return $this->belongsTo('App\Models\Department');
    }

    public function result() {
        return $this->belongsTo('App\Models\Result');
    }
}
