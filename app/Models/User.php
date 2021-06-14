<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    protected $table = 'u_users';
    
    protected $fillable = [
        'email',
        'name',
        'phone',
        'faculty_value',
        'u_type',
        'active',
    ];
}
