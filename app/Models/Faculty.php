<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function departments() {
        return $this->hasMany('App\Models\Department');
    }
}
