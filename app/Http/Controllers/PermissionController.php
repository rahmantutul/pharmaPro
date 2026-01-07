<?php

namespace App\Http\Controllers;


use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('dashboard.permission.index', ['dataList' => Permission::paginate(50)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $request->validate([
        'name' => 'required|unique:permissions,name',
        ]);

        $role = New Permission();
        $role->name = $request->name;
        $role->guard_name = 'admin';
        $role->save();
        return back()->with('success', 'Permission Created Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $dataInfo = Permission::find($id);
        // return view('dashboard.permission.edit',compact('dataInfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $request->validate([
        'name' => 'required|unique:permissions,name,'.$request->id,
        ]);

        $dataInfo=Permission::find($request->id);

        $dataInfo->name=$request->name;

        $dataInfo->save();

        return back()->with('success', 'Permission Updated Successfully!');
          
    }
    public function destroy($id)
    {
        Permission::where('id',$id)->delete();
         return redirect()->back()->with('success','Deleted Successfull');
    }

}
