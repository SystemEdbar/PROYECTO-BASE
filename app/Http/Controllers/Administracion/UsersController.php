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
        $role = Role::where('id', '!=', 1)->orderby('id', 'ASC')->get();
        $user = User::with('roles')->where('id', '!=', 1)->orderby('id', 'ASC')->get();
        if ($request->ajax()) {
            return response()->json($user);
        }
        return view('administracion.user.index', compact('user','role'));
    }

    public function store(PostRequestUsers $request)
    {
        try {
            $dataRequest = $request->validated();

            $dataRequest['password'] = $dataRequest['password'] ?? '1234567890';
            $dataRequest['password'] = Hash::make($dataRequest['password']);

            User::create($dataRequest);

            return response()->json(['status' => 'success','message' => 'Creado con éxito']);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    public function update(PostRequestUsers $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $dataRequest = $request->validated();

            $updateData = [
                'name' => $dataRequest['name'],
                'lastname' => $dataRequest['lastname'],
                'email' => $dataRequest['email']
            ];
            if($dataRequest['password']) {$updateData['password'] = Hash::make($dataRequest['password']);}
            $user->update($updateData);

            return response()->json(['status' => 'success','message' => 'Usuario Actualizado con éxito']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error','message' => $e->getMessage()]);
        }
    }

    public function roles(Request $request)
    {
        $roles = Role::where('id', '!=', 1)->orderBy('id', 'ASC')->get();
        return response()->json($roles);
    }


    public function updateRole(PostRequestAssingRole $request, $id)
    {
        try {
            $dataRequest = $request->validated();
            $user = User::findOrFail($id);
            $rol = Role::where('name', $dataRequest['name'])->firstOrFail();
            $isRoleAssigned = $user->getRoleNames()->contains($rol->name);

            if ($isRoleAssigned) {
                $user->removeRole($rol->name);
                $message = 'Desasignado con éxito';
            } else {
                $user->assignRole($rol->name);
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
            User::find($id)->delete();
            $message = ['status' => 'success', 'message' => 'Usuario Eliminado con exito'];
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => 'No se puede eliminar porque existe un excepcion en la base de datos'];
        }
        return response()->json($message);
    }

    public function suspended($id)
    {
        try {
            $gestion = User::findOrFail($id);

            $gestion->status = !$gestion->status; // invertimos el estado actual
            $gestion->save();

            $message = $gestion->status ?
                ['status' => 'success', 'message' => 'Usuario Habilitado con exito'] :
                ['status' => 'success', 'message' => 'Usuario Deshabilitado con exito'];
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $message = ['status' => 'error', 'message' => 'Usuario no encontrado'];
        } catch (\Exception $e) {
            $message = ['status' => 'error', 'message' => 'No se puede finalizar porque existe una excepción en la base de datos'];
        }

        return response()->json($message);
    }

}
