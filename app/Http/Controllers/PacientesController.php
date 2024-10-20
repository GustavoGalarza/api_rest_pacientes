<?php

namespace App\Http\Controllers;

use App\Models\pacientes;
use Illuminate\Http\Request;

class PacientesController extends Controller
{
    public function index()
    {
        $pacientes = pacientes::paginate(5);
        return response()->json($pacientes);
    }
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:1|max:100',
            'apellido' => 'required|string|min:1|max:100',
            'email' => 'required|email|max:100',
            'telefono' => 'required|string|max:15',
            'fecha_nacimiento' => 'required|string|max:10'
        ];
        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->all()
            ], 400);
        }
        $pacientes = new pacientes($request->input());
        $pacientes->save();
        return response()->json([
            'status' => true,
            'message' => 'Paciente creado satisfactoriamente'
        ], 200);
    }
    public function show($id)
    {
        $paciente = pacientes::find($id);

        if (!$paciente) {
            return response()->json(['status' => false, 'message' => 'Paciente no encontrado'], 404);
        }

        return response()->json(['status' => true, 'data' => $paciente], 200);
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'nombre' => 'required|string|min:1|max:100',
            'apellido' => 'required|string|min:1|max:100',
            'email' => 'required|email|max:100',
            'telefono' => 'required|string|max:15',
            'fecha_nacimiento' => 'required|max:10'
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->all()
            ], 400);
        }
        $paciente = pacientes::find($id);
        if (!$paciente) {
            return response()->json(['status' => false, 'message' => 'Paciente no encontrado'], 404);
        }
        $paciente->update($request->only(['nombre', 'apellido', 'email', 'telefono', 'fecha_nacimiento']));
        // Devolver la respuesta con los datos actualizados
        return response()->json([
            'status' => true,
            'message' => 'Paciente actualizado',
            'data' => $paciente // Devuelve los datos actualizados
        ], 200);
    }
    public function destroy($id)
{
    // Buscar el paciente por ID y verificar que existe
    $paciente = pacientes::find($id);
    if (!$paciente) {
        return response()->json(['status' => false, 'message' => 'Paciente no encontrado'], 404);
    }
    $paciente->delete();
    // Devolver la respuesta de éxito
    return response()->json([
        'status' => true,
        'message' => 'Paciente eliminado correctamente'
    ], 200);
    }
    public function all(){
        $pacientes = pacientes::all();
        return response()->json($pacientes);
    }
}
