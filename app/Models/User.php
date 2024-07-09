<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the vendor associated with the user.
     */
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    /**
     * Find a user by the User instance.
     *
     * @param User $user
     * @return User|null
     */
    public static function findByUser(User $user)
    {
        return self::find($user->id);
    }

    /**
     * Find a user by their ID.
     *
     * @param string $id
     * @return User|null
     */
    public static function findByID(string $id)
    {
        return self::find($id);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
