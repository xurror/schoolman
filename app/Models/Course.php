<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model {
    use Notifiable;
    use SoftDeletes;

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
