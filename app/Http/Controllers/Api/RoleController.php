<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Requests\RoleRule;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Role::all();
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
    public function store(RoleRule $request)
    {
        DB::beginTransaction();
        try {
            Role::create($request->all());
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
            $info = ['data' => Role::findOrFail($id)];
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
    public function update(RoleRule $request, $id)
    {
        DB::beginTransaction();
        try {
            Role::findOrFail($id)->update($request->all());
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
            Role::findOrFail($id)->delete();
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
