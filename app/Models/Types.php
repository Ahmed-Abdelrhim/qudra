<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $table = 'types';
    protected $guarded = ['id'];
    protected $fillable = [
        'name','status'
    ];
    protected $fakeColumns = [];

    public $timestamps = false;
}
