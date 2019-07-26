<?php

namespace App\Models;

class Source extends BaseModel
{

    protected $table = 'source';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'app_key', 'app_secret'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    public $updateFields = [
        'id', 'name', 'app_key', 'app_secret'
    ];
}
