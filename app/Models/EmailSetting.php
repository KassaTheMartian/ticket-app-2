<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    protected $fillable = [
        'mailer', 'host', 'port', 'username', 'password', 'encryption', 'from_address', 'from_name',
    ];
}
