<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    public $timestamps = true;
    
    public $incrementing = true;

    protected $table = 'category';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */    
    protected $fillable = ['id', 'name', 'type', 'model', 'created_at', 'updated_at'];

    // public function subCat()
    // {
    //     return $this->hasMany(Category::class,'parent_id','id');
    // }

    // public function applications()
    // {
    //     return $this->hasMany(Application::class,'subcategory_id','id');
    // }

    public function products()
    {
        return $this->hasMany(Product::class,'category','id');
    }
    
}
