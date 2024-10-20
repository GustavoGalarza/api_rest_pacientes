<?php

namespace App\Http\Controllers;

use App\Models\medicos;
use Illuminate\Http\Request;

class MedicosController extends Controller
{

    public function index()
    {
        $medicos = medicos::paginate(5);
        return response()->json($medicos);
    }
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:1|max:100',
            'especialidad' => 'required|max:100',
            'email' => 'required|email|max:100',
            'telefono' => 'required|string|max:15',
        ];
        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->all()
            ], 400);
        }
        $medicos = new medicos($request->input());
        $medicos->save();
        return response()->json([
            'status' => true,
            'message' => 'Medico agregado satisfactoriamente'
        ], 200);
    }
    public function show($id)
    {
        $paciente = medicos::find($id);
        if (!$paciente) {
            return response()->json(['status' => false, 'message' => 'Medico no encontrado'], 404);
        }

        return response()->json(['status' => true, 'data' => $paciente], 200);
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'nombre' => 'required|string|min:1|max:100',
            'especialidad' => 'required|max:100',
            'email' => 'required|email|max:100',
            'telefono' => 'required|string|max:15',
        ];
        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->all()
            ], 400);
        }
        $medicos = medicos::find($id);
        if (!$medicos) {
            return response()->json(['status' => false, 'message' => 'Medico no encontrado'], 404);
        }
        $medicos->update($request->only(['nombre', 'especialidad', 'email', 'telefono']));
        return response()->json([
            'status' => true,
            'message' => 'Medico Actualizado',
            'data'=>$medicos
        ], 200);
    }
    public function destroy($id)
    {
        // Buscar el paciente por ID y verificar que existe
    $medicos = medicos::find($id);
    if (!$medicos) {
        return response()->json(['status' => false, 'message' => 'Medico no encontrado'], 404);
    }
    $medicos->delete();
    // Devolver la respuesta de éxito
    return response()->json([
        'status' => true,
        'message' => 'Medico eliminado correctamente'
    ], 200);
    }
    public function all(){
        $medicos = medicos::all();
        return response()->json($medicos);
    }
}
