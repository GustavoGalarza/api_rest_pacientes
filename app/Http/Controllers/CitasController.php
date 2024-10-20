<?php

namespace App\Http\Controllers;
use App\Models\pacientes;
use App\Models\medicos;
use App\Models\citas;
use Illuminate\Http\Request;
use DB;

class CitasController extends Controller
{
    public function index()
    {
        $citas = citas::select('citas.*','pacientes.nombre as pacientes','medicos.nombre as medicos')
        ->join('pacientes','pacientes.id','=','citas.paciente_id')->join('medicos', 'medicos.id', '=', 'citas.medico_id')
        ->paginate(5);
        return response()->json($citas);
    }
    public function store(Request $request)
    {
        $rules = [
            'paciente_id' => 'required|numeric',
            'medico_id' => 'required|numeric',
            'fecha_cita' => 'required|string|max:100',
            'motivo' => 'required|string|max:200',
        ];
        
        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->all()
            ], 400);
        }
        $citas = new citas($request->input());
        $citas->save();
        return response()->json([
            'status' => true,
            'message' => 'Cita creado satisfactoriamente'
        ], 200); 
    }
    public function show($id)
    {
        $paciente = citas::find($id);
        if (!$paciente) {
            return response()->json(['status' => false, 'message' => 'Cita no encontrado'], 404);
        }

        return response()->json(['status' => true, 'data' => $paciente], 200);
    }
    public function update(Request $request,$id)
    {
        $rules = [
            'paciente_id' => 'required|numeric',
            'medico_id' => 'required|numeric',
            'fecha_cita' => 'required|string|max:100',
            'motivo' => 'required|string|max:200',
        ];
        
        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->all()
            ], 400);
        }
        $citas = citas::find($id);
        if (!$citas) {
            return response()->json(['status' => false, 'message' => 'Cita no encontrado'], 404);
        }
        $citas->update($request->only(['paciente_id', 'medico_id', 'fecha_cita', 'motivo']));
        return response()->json([
            'status' => true,
            'message' => 'Cita actualizada satisfactoriamente',
            'data' => $citas
        ], 200); 
    }
    public function destroy($id)
    {
        // Buscar el paciente por ID y verificar que existe
    $citas = citas::find($id);
    if (!$citas) {
        return response()->json(['status' => false, 'message' => 'Cita no encontrado'], 404);
    }
    $citas->delete();
    // Devolver la respuesta de éxito
    return response()->json([
        'status' => true,
        'message' => 'Cita eliminada correctamente'
    ], 200);
    }
    public function citasPorPacientes(){
        $citas =citas::select(DB::raw('count(citas.id) as count,pacientes.nombre'))
        ->join('pacientes','pacientes.id','=','citas.paciente_id')
        ->groupBy('pacientes.nombre')->get();
        return response()->json($citas);
    }
    public function citasPorMedicos(){
        $citas =citas::select(DB::raw('count(citas.id) as count,medicos.nombre'))
        ->join('medicos','medicos.id','=','citas.medico_id')
        ->groupBy('medicos.nombre')->get();
        return response()->json($citas);
    }
    public function all(){
        $citas = citas::select('citas.*','pacientes.nombre as pacientes','medicos.nombre as medicos')
        ->join('pacientes','pacientes.id','=','citas.paciente_id')->join('medicos', 'medicos.id', '=', 'citas.medico_id')
        ->get();
        return response()->json($citas);
    }
}
