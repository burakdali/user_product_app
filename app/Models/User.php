<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'phone_number',
        'is_admin'
    ];

    public function product()
    {
        return $this->belongsToMany(Product::class, "users_products");
    }
    public function id(): string
    {
        return (string) $this->id;
    }
    public function name(): string
    {
        return (string) $this->name;
    }
    public function email(): string
    {
        return (string) $this->email;
    }
    public function phoneNumber(): string
    {
        return (string) $this->phone_number;
    }
    public function isAdmin(): string
    {
        return (string) $this->is_admin;
    }
    public function password(): string
    {
        return (string) $this->password;
    }
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
}
