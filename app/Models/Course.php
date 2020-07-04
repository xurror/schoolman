<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model {
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'title', 'credits',
    ];

    public function staff() {
        return $this->belongsTo('App\Models\Staff');
    }

    public function result() {
        return $this->belongsTo('App\Models\Result');
    }

    public function department() {
        return $this->belongsTo('App\Models\Department');
    }

    public function students() {
        return $this->hasMany('App\Models\Student')->using('App\Models\CourseStudent');
    }
}
