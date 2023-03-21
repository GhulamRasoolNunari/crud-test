<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LaravelRepository\Traits\Authable;
use LaravelRepository\Traits\Filterable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Filterable, Authable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'dob',
        'address',
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

    protected $appends = [
        'full_name',
    ];

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Interest::class, 'user_interests')->withTimestamps();
    }
    
    public function dob(): Attribute
    {
        return new Attribute(
            fn ($value) => Carbon::parse($value)->format('m/d/Y'),
            fn ($value) => Carbon::parse($value)->format('Y-m-d')
        );
    }

    public function fullName(): Attribute
    {
        return new Attribute(
            fn ($value) => $this->first_name.' '.$this->last_name
        );
    }

    protected function extendValidation(): array
    {
        $valid = true;
        $message = null;
        if (!$this->email_verified_at) {
            $valid = false;
            $message = 'Please Verify you email address first';
        }

        return [$valid, $message];
    }
}
