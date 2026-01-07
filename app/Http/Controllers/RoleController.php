<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index($id=null)
    {
        $all_roles = Role::all();
        if($id){
            $roles = Role::with('permissions')->find($id);
            if (!$roles) {
                return redirect()->route('role.index')->with('error', 'Role not found.');
            }
            $permission_list =  Permission::all();
            $group = Permission::distinct()->get();
    
            $generalscontroller = new GeneralsController();
            $role_name = $generalscontroller->RoleName($id);
    
            return view ('dashboard.role.index',compact('permission_list','id','role_name','group','roles','all_roles'));

        }else{
            return view('dashboard.role.index',compact('all_roles'));
        }

    }

    public function create()
    {
        return view('dashboard.role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
    ]);
    
        $role = New Role();
        $role->name = $request->name;
        $role->guard_name = 'admin';
        $role->save();
        return back()->with('success', 'Role Created Successfully!');
    }

    public function edit($id)
    {
        $dataInfo = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('dashboard.role.edit',compact('dataInfo','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);
    
        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();
    
        return redirect()->route('role.index')->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        try{
          Role::where('id',$id)->delete();
        }catch (\Exception $e){
          return redirect()->back()->with('error',$e->getMessage());
        }
        return redirect()->route('role.index')->with('success','Role Deleted Successfully');

    }


    public function access_store(Request $request)
    {
        $request->validate([
            'permissions' => 'required',
        ]);
        $id = $request->id;
        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permissions);
        return back()->with('success','Role has been mapped successfully');
    }
}
