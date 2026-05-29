<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'address',
        'date_of_birth',
        'national_id',
        'passport_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Auto Full Name
    |--------------------------------------------------------------------------
    */

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = $value;

        $this->setFullName();
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = $value;

        $this->setFullName();
    }

    protected function setFullName()
    {
        $first = $this->attributes['first_name'] ?? '';

        $last = $this->attributes['last_name'] ?? '';

        $this->attributes['name'] = trim($first . ' ' . $last);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDirector()
    {
        return $this->role === 'director';
    }
}
