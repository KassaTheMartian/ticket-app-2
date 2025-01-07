<?php

namespace App\Models;

use Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Customer extends Authenticatable implements MustVerifyEmail
{

    use HasFactory, Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'gender',
        'date_of_birth',
        'profile_picture',
        'software',
        'website',
        'tax_number',
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
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function messages()
    {
        return $this->morphMany(Message::class, 'sender');
    }
}
