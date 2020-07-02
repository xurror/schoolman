<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model {
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dor', 'class',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function department() {
        return $this->belongsTo('App\Models\Department');
    }

    public function courses() {
        return $this->hasMany('App\Models\Course');
    }

    public function results() {
        return $this->hasMany('App\Models\Result');
    }

    public function fees() {
        return $this->hasMany('App\Models\Fee');
    }
}
