<?php

namespace App\Models;

class ApiDetails extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'url', 'type', 'source_id', 'request_method'
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
        'id', 'name', 'code', 'url', 'type', 'source_id', 'request_method'
    ];
}
