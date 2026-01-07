<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    // Display a listing of the admins// Display the Admin list page
    public function index()
    {
        $roleList = Role::all();
        return view('dashboard.admins.index',compact('roleList'));
    }

    // Return Admin data for DataTables
    public function getAdminsData()
    {
        $admins = Admin::select(['id', 'name', 'email', 'phone','roleId'])
            ->with('role') // Eager load the role relationship
            ->get();
        return DataTables::of($admins)
            ->addColumn('role', function($admin) {
                return $admin->role ? $admin->role->name : 'N/A'; // Display role name or 'N/A' if no role
            })
            ->addColumn('actions', function($admin) {
                return '
                    <a href="'.route('admin.edit', $admin->id).'" class="btn btn-warning btn-xs">Edit</a>
                    <form action="'.route('admin.destroy', $admin->id).'" method="POST" style="display:inline;">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm(\'Are you sure?\')">Delete</button>
                    </form>
                ';
            })
            ->rawColumns(['actions']) // Allow HTML rendering for actions
            ->make(true);
    }

    // Store new admin
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roleId' => 'required|exists:roles,id',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'roleId' => $request->roleId,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        
        // Assign the role to the user using Spatie's method
        $role = Role::find($request->roleId);
        if ($role) {
            $admin->assignRole($role->name);
        }
        
        return redirect()->route('admin.index')->with('success', 'Admin created successfully!');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $roleList = Role::all();
        return view('dashboard.admins.edit', compact('admin','roleList'));
    }

    // Update existing admin
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'roleId' => 'required|exists:roles,id',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'roleId' => $request->roleId,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $admin->update($updateData);

        // Sync the role
        $role = Role::find($request->roleId);
        if ($role) {
            $admin->syncRoles([$role->name]);
        }

        return redirect()->route('admin.index')->with('success', 'Admin updated successfully!');
    }
    

    // Delete an admin
    public function destroy($id)
    {
        Admin::findOrFail($id)->delete();

        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully!');
    }
}
