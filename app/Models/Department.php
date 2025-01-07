<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'name', 
        'description', 
    ];
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
