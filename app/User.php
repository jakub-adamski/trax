<?php

namespace App;

use App\Models\Car\CarTrip;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 *
 * @property integer id
 * @property string name
 * @property string email
 * @property string password
 *
 * @property Collection|Car[] cars
 *
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function carsTrips(): HasManyThrough
    {
        return $this->hasManyThrough(CarTrip::class, Car::class);
    }
}
