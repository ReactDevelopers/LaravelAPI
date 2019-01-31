<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    public $timestamps = true;
    
    public $incrementing = true;

    protected $table = 'users';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */    
    protected $fillable = ['id', 'name', 'email', 'password', 'profile_image', 'role_id', 'created_at', 'updated_at', 'deleted_at'];
    
    public static function  all($fetch = 'array', $keys = ['*'], $where = "", $order_by = 'static_content_id'){
        $table_pages = DB::table('static_content');

        if(!empty($keys)){
            $table_pages->select($keys); 
        }

        if(!empty($where)){
            $table_pages->whereRaw($where); 
        }
        
        $table_pages->orderBy($order_by); 

        if($fetch === 'array'){
            return json_decode(
                json_encode(
                    $table_pages->get()
                ),
                true
            );
        }else{
            return $table_pages->get();
        }
    }
}
