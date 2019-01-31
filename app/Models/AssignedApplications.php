<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AssignedApplications extends Model
{
    public $timestamps = true;
    
    public $incrementing = true;

    protected $table = 'assignedapplications';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */    
    protected $fillable = ['id', 'user_id', 'application_id', 'status', 'created_at', 'updated_at'];
    
}
