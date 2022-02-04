<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'model_has_roles';

    protected $fillable = ['role_id','model_type','model_id'];

}
