<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    public $timestamps = true;
    
    public $incrementing = true;

    protected $table = 'cart';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */    
    protected $fillable = ['id', 'user_id', 'product_id', 'quantity', 'created_at', 'updated_at'];
    

    public function productdetail()
    {
        return $this->hasMany(Product::class,'id','product_id');
    }
}
