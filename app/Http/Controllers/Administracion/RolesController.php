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
        $role = Role::with('permissions')->where('id', '!=', 1)->get();
        $permission = Permission::all();
        if ($request->ajax()) {
            return response()->json($role);
        }
        return view('administracion.role.index', compact('permission','role'));
    }

    public function permission()
    {
        $permission = Permission::orderBy('id')->get();
        return response()->json($permission);
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

    public function updatePermission(PostRequestAssingRole $request, $id)
    {
        try {
            $dataRequest = $request->validated();
            $role = Role::findOrFail($id);
            $permission = Permission::where('name', $dataRequest['name'])->firstOrFail()->name;
            $isPermissionAssigned = $role->hasPermissionTo($permission);

            if ($isPermissionAssigned) {
                $role->revokePermissionTo($permission);
                $message = 'Desasignado con éxito';
            } else {
                $role->givePermissionTo($permission);
                $message = 'Asignado con éxito';
            }
            return response()->json(['status' => 'success', 'message' => $message]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
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
