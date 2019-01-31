<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    public $timestamps = true;
    
    public $incrementing = true;

    protected $table = 'user_detail';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */    
    protected $fillable = [
    						'id',
    						'user_id',
    						'company_id',
    						'salutation',
    						'first_name',
    						'last_name',
    						'role',
    						'status'
    					];




    public function user()
    {
        return $this->hasOne('App\User');
    }
}
