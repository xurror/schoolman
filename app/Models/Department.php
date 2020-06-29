<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function faculty() {
        return $this->belongsTo('App\Models\Faculty');
    }

    public function courses() {
        return $this->hasMany('App\Models\Course');
    }

    public function staff() {
        return $this->hasMany('App\Models\Staff');
    }

    public function students() {
        return $this->hasMany('App\Models\Student');
    }

}
