<?php

namespace App\Http\Controllers;

use App\Models\medicos;
use Illuminate\Http\Request;


class MedicosController extends Controller
{
    /**
     * @OA\Get(
     *     path="/medicos",
     *     summary="Obtener lista de médicos",
     *     description="Devuelve todos los médicos registrados (Requiere autenticación)",
     *     tags={"Médicos"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de médicos obtenida con éxito",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Dr. Juan Pérez"),
     *                 @OA\Property(property="especialidad", type="string", example="Cardiología"),
     *                 @OA\Property(property="telefono", type="string", example="123456789"),
     *                 @OA\Property(property="email", type="string", example="juan.perez@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $medicos = medicos::paginate(5);
        return response()->json($medicos);
    }
    /**
     * @OA\Post(
     *     path="/medicos",
     *     summary="Agregar un médico",
     *     description="Crea un nuevo médico (Requiere autenticación)",
     *     tags={"Médicos"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "especialidad", "email", "telefono"},
     *             @OA\Property(property="nombre", type="string", example="Dr. Juan Pérez"),
     *             @OA\Property(property="especialidad", type="string", example="Cardiología"),
     *             @OA\Property(property="email", type="string", example="juan.perez@example.com"),
     *             @OA\Property(property="telefono", type="string", example="123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Médico agregado satisfactoriamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Médico agregado satisfactoriamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="error", type="array", @OA\Items(type="string"))
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
    /**
     * @OA\Get(
     *     path="/medicos/{id}",
     *     summary="Obtener un médico por ID",
     *     description="Devuelve un médico específico (Requiere autenticación)",
     *     tags={"Médicos"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Médico encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Dr. Juan Pérez"),
     *                 @OA\Property(property="especialidad", type="string", example="Cardiología"),
     *                 @OA\Property(property="telefono", type="string", example="123456789"),
     *                 @OA\Property(property="email", type="string", example="juan.perez@example.com")
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
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $paciente = medicos::find($id);
        if (!$paciente) {
            return response()->json(['status' => false, 'message' => 'Medico no encontrado'], 404);
        }

        return response()->json(['status' => true, 'data' => $paciente], 200);
    }
    /**
     * @OA\Put(
     *     path="/medicos/{id}",
     *     summary="Actualizar un médico",
     *     description="Actualiza un médico existente (Requiere autenticación)",
     *     tags={"Médicos"},
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
     *             required={"nombre", "especialidad", "email", "telefono"},
     *             @OA\Property(property="nombre", type="string", example="Dr. Juan Pérez"),
     *             @OA\Property(property="especialidad", type="string", example="Cardiología"),
     *             @OA\Property(property="email", type="string", example="juan.perez@example.com"),
     *             @OA\Property(property="telefono", type="string", example="123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Médico actualizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Médico actualizado"),
     *             @OA\Property(property="data", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Dr. Juan Pérez"),
     *                 @OA\Property(property="especialidad", type="string", example="Cardiología"),
     *                 @OA\Property(property="telefono", type="string", example="123456789"),
     *                 @OA\Property(property="email", type="string", example="juan.perez@example.com")
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
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="error", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
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
            'data' => $medicos
        ], 200);
    }
    /**
     * @OA\Delete(
     *     path="/medicos/{id}",
     *     summary="Eliminar un médico",
     *     description="Elimina un médico existente (Requiere autenticación)",
     *     tags={"Médicos"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Médico eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Médico eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Médico no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Médico no encontrado")
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
    /**
 * @OA\Get(
 *     path="/medicosAll",
 *     summary="Obtener todos los médicos",
 *     description="Devuelve todos los médicos registrados (Requiere autenticación)",
 *     tags={"Médicos"},
 *     security={{ "sanctum": {} }},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de médicos obtenida con éxito",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(type="object", 
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="nombre", type="string", example="Dr. Juan Pérez"),
 *                 @OA\Property(property="especialidad", type="string", example="Cardiología"),
 *                 @OA\Property(property="telefono", type="string", example="123456789"),
 *                 @OA\Property(property="email", type="string", example="juan.perez@example.com")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     )
 * )
 */
    public function all()
    {
        $medicos = medicos::all();
        return response()->json($medicos);
    }
}
