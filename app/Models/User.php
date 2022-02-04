<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Lumen\Auth\Authorizable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property string phone
 * @property string first_name
 * @property int id
 * @property string token
 * @method find($primaryKey)
 */

/**
 * @method \Illuminate\Database\Eloquent\Builder where(string $dbField, mixed $operator = null, mixed $value = null)
 * @method \Illuminate\Database\Eloquent\Builder whereBetween(string $dbField, string[] $fromDateAndToDate)
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'first_name',
        'second_name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'created_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function myRoles(): HasManyThrough
    {
        return $this->hasManyThrough(
            Role::class,
            ModelHasRole::class,
            'model_id',
            'id',
            'id',
            'role_id'
        );
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [$this->id ,$this->phone, $this->first_name];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
}
