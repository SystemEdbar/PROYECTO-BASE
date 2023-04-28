<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\PostRequestAssingRole;
use App\Models\Admin\MenuUrl;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::with('permissions')->get();
        $permission = Permission::all();
        $menu = new MenuUrl();
        if ($request->ajax()) {
            return response()->json($role);
        }else {
            return view('administracion.role.index', compact('permission','role', 'menu'));
        }
    }

    public function store(Request $request)
    {
        $dataRequest = $request->validate([
            'name' => 'required|unique:roles',
        ]);
        try {
            Role::create([
                'name' => $dataRequest['name'],
                'guard_name' => 'web'
            ]);
            $message =['status' => 'success', 'message' => 'Creado con exito'];
        }catch (Exception $e){
            $message =['status' => 'error', 'message' =>$e->getMessage()];
        }
        return response()->json($message);
    }

    public function permission(Request $request)
    {
        $permission = Permission::orderBy('id')->get();
        return response()->json($permission);
    }

    public function updatePermission(PostRequestAssingRole $request, $id)
    {
        $dataRequest = $request->validated();
        $role = Role::find($id);
        $permission = Permission::where('name', $dataRequest['name'])->pluck('name')->first();
        $status = $role->hasPermissionTo($permission);
        if ($status) {
            $message = ['status' => 'success', 'message' => 'Desasignado con exito'];
            $role->revokePermissionTo($permission);
        } else {
            $message = ['status' => 'success', 'message' => 'Asignado con exito'];
            $role->givePermissionTo($permission);
        }
        return response()->json($message);
    }

    public function updateRole(PostRequestAssingRole $request, $id)
    {
        $dataRequest = $request->validated();
        $user = User::find($id);
        $rol = Role::where('name', $dataRequest['name'])->first();
        $roles = $user->getRoleNames();
        $status = in_array($rol->name, $roles->toArray());
        if ($status) {
            $message = ['status' => 'success', 'message' => 'Desasignado con exito'];
            $user->removeRole($rol->name);
        } else {
            $message = ['status' => 'success', 'message' => 'Asignado con exito'];
            $user->assignRole($rol->name);
        }
        return response()->json($message);
    }

    public function destroy($id)
    {
        try {
            Role::find($id)->delete();
            $message = ['status' => 'success', 'message' => 'Role Eliminado con exito'];
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => 'No se puede eliminar porque existe un excepcion en la base de datos'];
        }
        return response()->json($message);
    }
}
