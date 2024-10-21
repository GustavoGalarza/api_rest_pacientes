<?php

namespace App\Http\Controllers;
use App\Models\pacientes;
use App\Models\medicos;
use App\Models\citas;
use Illuminate\Http\Request;
use DB;

class CitasController extends Controller
{
    /**
     * @OA\Get(
     *     path="/citas",
     *     summary="Obtener lista de citas",
     *     description="Devuelve una lista de citas paginadas (Requiere autenticación)",
     *     tags={"Citas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de citas obtenida con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="paciente_id", type="integer", example=1),
     *                     @OA\Property(property="medico_id", type="integer", example=1),
     *                     @OA\Property(property="fecha_cita", type="string", format="date-time", example="2024-10-20T10:00:00"),
     *                     @OA\Property(property="motivo", type="string", example="Consulta general"),
     *                     @OA\Property(property="pacientes", type="string", example="Juan Pérez"),
     *                     @OA\Property(property="medicos", type="string", example="Dr. López"),
     *                 )
     *             ),
     *             @OA\Property(property="total", type="integer", example=50),
     *             @OA\Property(property="per_page", type="integer", example=5),
     *             @OA\Property(property="last_page", type="integer", example=10),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */

    public function index()
    {
        $citas = citas::select('citas.*', 'pacientes.nombre as pacientes', 'medicos.nombre as medicos')
            ->join('pacientes', 'pacientes.id', '=', 'citas.paciente_id')->join('medicos', 'medicos.id', '=', 'citas.medico_id')
            ->paginate(5);
        return response()->json($citas);
    }
    /**
     * @OA\Post(
     *     path="/citas",
     *     summary="Crear una nueva cita",
     *     description="Registra una nueva cita (Requiere autenticación)",
     *     tags={"Citas"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"paciente_id", "medico_id", "fecha_cita", "motivo"},
     *             @OA\Property(property="paciente_id", type="integer", example=1),
     *             @OA\Property(property="medico_id", type="integer", example=1),
     *             @OA\Property(property="fecha_cita", type="string", example="2024-10-20 10:00:00"),
     *             @OA\Property(property="motivo", type="string", example="Consulta general")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cita creada satisfactoriamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cita creado satisfactoriamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="error", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/citas/{id}",
     *     summary="Obtener una cita por ID",
     *     description="Devuelve una cita específica (Requiere autenticación)",
     *     tags={"Citas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cita encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="paciente_id", type="integer", example=1),
     *                 @OA\Property(property="medico_id", type="integer", example=1),
     *                 @OA\Property(property="fecha_cita", type="string", format="date-time", example="2024-10-20T10:00:00"),
     *                 @OA\Property(property="motivo", type="string", example="Consulta general"),
     *                 @OA\Property(property="pacientes", type="string", example="Juan Pérez"),
     *                 @OA\Property(property="medicos", type="string", example="Dr. López"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cita no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cita no encontrado")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        $paciente = citas::find($id);
        if (!$paciente) {
            return response()->json(['status' => false, 'message' => 'Cita no encontrado'], 404);
        }

        return response()->json(['status' => true, 'data' => $paciente], 200);
    }
    /**
     * @OA\Put(
     *     path="/citas/{id}",
     *     summary="Actualizar una cita por ID",
     *     description="Actualiza una cita específica (Requiere autenticación)",
     *     tags={"Citas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"paciente_id", "medico_id", "fecha_cita", "motivo"},
     *             @OA\Property(property="paciente_id", type="integer", example=1),
     *             @OA\Property(property="medico_id", type="integer", example=1),
     *             @OA\Property(property="fecha_cita", type="string", example="2024-10-20 10:00:00"),
     *             @OA\Property(property="motivo", type="string", example="Consulta general")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cita actualizada satisfactoriamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cita actualizada satisfactoriamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="paciente_id", type="integer", example=1),
     *                 @OA\Property(property="medico_id", type="integer", example=1),
     *                 @OA\Property(property="fecha_cita", type="string", example="2024-10-20 10:00:00"),
     *                 @OA\Property(property="motivo", type="string", example="Consulta general"),
     *                 @OA\Property(property="pacientes", type="string", example="Juan Pérez"),
     *                 @OA\Property(property="medicos", type="string", example="Dr. López"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cita no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cita no encontrado")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
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
    /**
     * @OA\Delete(
     *     path="/citas/{id}",
     *     summary="Eliminar una cita por ID",
     *     description="Elimina una cita específica (Requiere autenticación)",
     *     tags={"Citas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cita eliminada satisfactoriamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cita eliminada satisfactoriamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cita no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cita no encontrado")
     *         )
     *     )
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/citas/pacientes/{paciente_id}",
     *     summary="Obtener citas por paciente",
     *     description="Devuelve todas las citas de un paciente específico (Requiere autenticación)",
     *     tags={"Citas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="paciente_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Citas encontradas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="count", type="integer", example=5),
     *                     @OA\Property(property="nombre", type="string", example="Juan Pérez")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paciente no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Paciente no encontrado")
     *         )
     *     )
     * )
     */
    public function citasPorPacientes()
    {
        $citas = citas::select(DB::raw('count(citas.id) as count,pacientes.nombre'))
            ->join('pacientes', 'pacientes.id', '=', 'citas.paciente_id')
            ->groupBy('pacientes.nombre')->get();
        return response()->json($citas);
    }
    /**
     * @OA\Get(
     *     path="/citas/medicos/{medico_id}",
     *     summary="Obtener citas por médico",
     *     description="Devuelve todas las citas de un médico específico (Requiere autenticación)",
     *     tags={"Citas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="medico_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Citas encontradas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="count", type="integer", example=5),
     *                     @OA\Property(property="nombre", type="string", example="Dr. Juan Pérez")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Médico no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Médico no encontrado")
     *         )
     *     )
     * )
     */
    public function citasPorMedicos()
    {
        $citas = citas::select(DB::raw('count(citas.id) as count,medicos.nombre'))
            ->join('medicos', 'medicos.id', '=', 'citas.medico_id')
            ->groupBy('medicos.nombre')->get();
        return response()->json($citas);
    }
    /**
     * @OA\Get(
     *     path="/citasAll",
     *     summary="Obtener todas las citas",
     *     description="Devuelve todas las citas junto con la información de los pacientes y médicos (Requiere autenticación)",
     *     tags={"Citas"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Citas encontradas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="paciente_id", type="integer", example=1),
     *                     @OA\Property(property="medico_id", type="integer", example=1),
     *                     @OA\Property(property="fecha_cita", type="string", example="2024-10-20 10:00:00"),
     *                     @OA\Property(property="motivo", type="string", example="Consulta general"),
     *                     @OA\Property(property="pacientes", type="string", example="Juan Pérez"),
     *                     @OA\Property(property="medicos", type="string", example="Dr. Ana Gómez")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron citas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No se encontraron citas")
     *         )
     *     )
     * )
     */
    public function all()
    {
        $citas = citas::select('citas.*', 'pacientes.nombre as pacientes', 'medicos.nombre as medicos')
            ->join('pacientes', 'pacientes.id', '=', 'citas.paciente_id')->join('medicos', 'medicos.id', '=', 'citas.medico_id')
            ->get();
        return response()->json($citas);
    }
}
