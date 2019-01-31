<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    public $timestamps = true;
    
    public $incrementing = true;

    protected $table = 'product';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */    
    protected $fillable = ['id', 'name', 'category', 'description', 'price', 'make', 'created_at', 'updated_at'];
    
}
