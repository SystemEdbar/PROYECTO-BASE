<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\PostRequestAssingRole;
use App\Http\Requests\Administracion\PostRequestUsers;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::orderBy('id')->where('id', '!=', 1)->orderby('id', 'ASC')->get();
        $user = User::with('roles')->where('id', '!=', 1)->orderby('id', 'ASC')->get();
        if ($request->ajax()) {
            return response()->json($user);
        }else {
            return view('administracion.user.index', compact('user','role'));
        }
    }

    public function store(PostRequestUsers $request)
    {
        try {
            $dataRequest = $request->validated();
            if($dataRequest['password']===null){
                $dataRequest['password']='Afera1801';
            }
            User::create(['name' => $dataRequest['name'], 'lastname' => $dataRequest['lastname'],
                'dpi' => $dataRequest['dpi'], 'email' => $dataRequest['email'], 'password' => Hash::make($dataRequest['password'])]);
            $message =['status' => 'success', 'message' => 'Creado con exito'];
        }catch (Exception $e){
            $message =['status' => 'error', 'message' =>$e->getMessage()];
        }
        return response()->json($message);
    }

    public function update(PostRequestUsers $request, $id)
    {
        $user = User::find($id);
        $dataRequest = $request->validated();
        try {
            if($dataRequest['password'] === null) {
                $user->update(['name' => $dataRequest['name'], 'lastname' => $dataRequest['lastname'],
                    'dpi' => $dataRequest['dpi'], 'email' => $dataRequest['email']]);
            }
            else{
                $user->update(['name' => $dataRequest['name'], 'lastname' => $dataRequest['lastname'],
                    'dpi' => $dataRequest['dpi'], 'email' => $dataRequest['email'], 'password' => Hash::make($dataRequest['password'])]);
            }
            $message = ['status' => 'success', 'message' => 'Usuario Actualizado con exito'];
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }
        return response()->json($message);
    }
    public function roles(Request $request)
    {
        $role = Role::orderBy('id')->where('id', '!=', 1)->orderby('id', 'ASC')->get();
        return response()->json($role);
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
            User::find($id)->delete();
            $message = ['status' => 'success', 'message' => 'Usuario Eliminado con exito'];
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => 'No se puede eliminar porque existe un excepcion en la base de datos'];
        }
        return response()->json($message);
    }
}
