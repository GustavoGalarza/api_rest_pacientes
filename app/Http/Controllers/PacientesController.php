<?php

namespace App\Http\Controllers;

use App\Models\pacientes;
use Illuminate\Http\Request;
/**
 * @OA\Info(
 *     title="APIREST Sistema de Pacientes",
 *     version="1.0.0",
 *     description="Documentación de la API para gestionar el sistema pacientes",
 *     @OA\Contact(
 *         name="Gustavo Galarza- Daniel Mamani",
 *         email="https://github.com/GustavoGalarza/sistema-pos.git"
 *     )
 * )
 */
class PacientesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pacientes",
     *     summary="Obtener lista de pacientes",
     *     description="Devuelve todos los pacientes (Requiere autenticación)",
     *     tags={"Pacientes"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pacientes obtenida con éxito",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="John"),
     *                 @OA\Property(property="apellido", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="telefono", type="string", example="1234567890"),
     *                 @OA\Property(property="fecha_nacimiento", type="string", example="1990-01-01")
     *             )
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
        $pacientes = pacientes::paginate(5);
        return response()->json($pacientes);
    }
    /**
     * @OA\Post(
     *     path="/pacientes",
     *     summary="Crear un nuevo paciente",
     *     description="Crea un nuevo paciente (Requiere autenticación)",
     *     tags={"Pacientes"},
     *     security={{ "sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="John"),
     *             @OA\Property(property="apellido", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="telefono", type="string", example="1234567890"),
     *             @OA\Property(property="fecha_nacimiento", type="string", example="1990-01-01")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paciente creado satisfactoriamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Paciente creado satisfactoriamente")
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
    /**
     * @OA\Get(
     *     path="/pacientes/{id}",
     *     summary="Obtener paciente por ID",
     *     description="Devuelve un paciente por ID (Requiere autenticación)",
     *     tags={"Pacientes"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paciente encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="John"),
     *                 @OA\Property(property="apellido", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="telefono", type="string", example="1234567890"),
     *                 @OA\Property(property="fecha_nacimiento", type="string", example="1990-01-01")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paciente no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Paciente no encontrado")
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
    public function show($id)
    {
        $paciente = pacientes::find($id);

        if (!$paciente) {
            return response()->json(['status' => false, 'message' => 'Paciente no encontrado'], 404);
        }

        return response()->json(['status' => true, 'data' => $paciente], 200);
    }
    /**
     * @OA\Put(
     *     path="/pacientes/{id}",
     *     summary="Actualizar paciente por ID",
     *     description="Actualiza un paciente existente por ID (Requiere autenticación)",
     *     tags={"Pacientes"},
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
     *             @OA\Property(property="nombre", type="string", example="John"),
     *             @OA\Property(property="apellido", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="telefono", type="string", example="1234567890"),
     *             @OA\Property(property="fecha_nacimiento", type="string", example="1990-01-01")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paciente actualizado satisfactoriamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Paciente actualizado satisfactoriamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paciente no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Paciente no encontrado")
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
    /**
     * @OA\Delete(
     *     path="/pacientes/{id}",
     *     summary="Eliminar paciente por ID",
     *     description="Elimina un paciente por ID (Requiere autenticación)",
     *     tags={"Pacientes"},
     *     security={{ "sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paciente eliminado satisfactoriamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Paciente eliminado satisfactoriamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Paciente no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Paciente no encontrado")
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
    /**
     * @OA\Get(
     *     path="/pacientesAll",
     *     summary="Obtener todos los pacientes",
     *     description="Devuelve una lista de todos los pacientes (Requiere autenticación)",
     *     tags={"Pacientes"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Pacientes encontrados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Juan Pérez"),
     *                     @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1990-01-01"),
     *                     @OA\Property(property="telefono", type="string", example="123456789"),
     *                     @OA\Property(property="direccion", type="string", example="Calle Falsa 123")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron pacientes",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No se encontraron pacientes")
     *         )
     *     )
     * )
     */

    public function all()
    {
        $pacientes = pacientes::all();
        return response()->json($pacientes);
    }
}
