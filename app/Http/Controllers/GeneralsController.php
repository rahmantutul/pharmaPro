<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class GeneralsController extends Controller
{


    
    public function RoleName($id)
    {
        $role = Role::query()
              ->selectRaw('name')
              ->where('id', $id)->first();
        return $role ? $role->name : 'N/A';
    }

}