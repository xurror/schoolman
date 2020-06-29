<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ca_mark', 'exam_mark', 'max_mark', 'grade',
    ];

    public function student() {
        return $this->belongsTo('App\Models\Student');
    }

    public function courses() {
        return $this->hasMany('App\Models\Course');
    }

}
