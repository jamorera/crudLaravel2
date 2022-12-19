<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = User::with('roles:title')->get();
            if ($data->isNotEmpty()) {
                $info = [
                    "data" => $data,
                    "msg" => "Datos generados correctamente.",
                ];
                $code = 200;
            }else{
                $info = [
                    "msg" => "No hay registros",
                ];
                $code = 400;
            }
        } catch (\Throwable $th) {
            $info =["msg" => "No ha sido posible traer registros, por favor verifique e intente nuevamente."];
            $code = 500;
        }
        return response()->json($info,$code);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRule $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('role');
            if ($request->file('image')) {
                $url = Storage::put('public/profiles', $request->file('image'));
                $data['image'] = $url;
                $user = User::create($data);
                $user->roles()->sync($request->role);
            }
            $info = ['msg' => 'Datos registrados correctamente'];
            $code = 200;
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $info = ['msg' => 'No ha sido posible registrar los datos, por favor verifique e intente nuevamente.'];
            $code = 500;
        }
        return response()->json($info,$code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $info = ['data' => User::findOrFail($id)];
            $code = 200;
        } catch (\Throwable $th) {
            $info = ["msg" => "No ha sido posible realizar proceso, por favor verifique e intente nuevamente."];
            $code= 500;
        }
        return response()->json($info,$code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('role');
            if ($request->file('image')) {
                $user = User::findOrFail($id);
                $url = Storage::put('public/profiles', $request->file('image'));
                Storage::delete($user->image);
                $data['image'] = $url;
            }
            $user->update($data);
            $user->roles()->sync($request->role);
            DB::commit();
            $info =["msg" => "Datos actualizados correctamente"];
            $code = 200;
        } catch (\Throwable $th) {
            DB::rollback();
            $info =[
                "msg"=>"No ha sido posible realizar proceso, por favor verifique e intente nuevamente."
            ];
            $code = 500;
        }
        return response()->json($info,$code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            Storage::delete($user->image);
            $user->roles()->detach();
            $info = [
                "msg" => "Registro eliminado correctamente."
            ];
            $code =200;
        } catch (\Throwable $th) {
            $info = [
                "msg" => "No ha sido posible eliminar registro, por favor verifique e intente nuevamente."
            ];
            $code = 500;
        }
        return response()->json($info,$code);
    }
}
