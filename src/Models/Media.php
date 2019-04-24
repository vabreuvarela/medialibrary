<?php

namespace Spotmarket\MediaLibrary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    public static $rules = [
        'link' => 'required',
        'position' => 'required',
        'size' => 'required',
        'model_id' => 'required',
        'model_type' => 'required'
    ];

    protected $fillable = [
        'link',
        'position',
        'size',
        'group',
        'model_id',
        'model_type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
