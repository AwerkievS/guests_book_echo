<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name'];

    const USER_ROLE_ID = 1;
    const ADMIN_ROLE_ID = 2;




}
