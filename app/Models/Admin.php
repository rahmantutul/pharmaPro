<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasRoles;
    protected $guard_name = 'admin';
    protected $table = 'admins';  // Define the correct table if necessary
    
    protected $fillable = ['name', 'email', 'phone', 'password','roleId'];
    public function role()
    {
        return $this->belongsTo(Role::class,'roleId','id');
    }
}
