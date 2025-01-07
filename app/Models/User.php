<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'gender',
        'date_of_birth',
        'profile_picture',
        'email_verified_at',
        'admin',
        'department_id',
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

    public function messages()
    {
        return $this->morphMany(Message::class, 'sender');
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
    public function hasPermission($route){
        $routes = $this->routes();
        return in_array($route,$routes)? true : false;
    }
    public function routes(){
        $data = [];
        $roles = $this->roles;
        foreach($roles as $role){
            $permission = json_decode($role->permissions);
            foreach($permission as $per){
                if(!in_array($per,$data)){
                    array_push($data, $per);
                }

            }            
        }
        return $data;
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
    public function ticketlogs()
    {
        return $this->hasMany(TicketLog::class);
    }
}
