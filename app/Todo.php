<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
	protected $table = 'todo';
	public $timestamps = false;
    protected $fillable = [
        'id', 'name','category_id', 'created_at', 'updated_at'
    ];
}
