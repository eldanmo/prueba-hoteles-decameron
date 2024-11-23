<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    /**
     * Creación de la habitación.
     *
     * Valida los datos de entrada. 
     * Crea el elemento en la base de datos.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con los datos de la habitacion.
     * 
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON con un mensaje de éxito 
     *                                       si la habitación se crea correctamente o un mensaje 
     *                                       de error en caso de que falle.
    */

    public function store(Request $request) {

        try {

            //Valida los datos de entrada
            $validatedData = $request->validate([
                'id_hotel' => 'required|numeric',
                'cantidad' => 'required|numeric',
                'tipo_habitacion' => 'required|string',
                'acomodacion' => 'required|string',
            ]);

            // Verificar si ya existe una habitación con el mismo tipo y acomodación para el hotel
            $existeHabitacion = Habitacion::where('id_hotel', $validatedData['id_hotel'])
                ->where('tipo_habitacion', $validatedData['tipo_habitacion'])
                ->where('acomodacion', $validatedData['acomodacion'])
                ->where('estado', '!=', 'ELIMINADO')
                ->exists();

            if ($existeHabitacion) {
                return response()->json([
                    'error' => 'Ya existe una habitación con el mismo tipo y acomodación para este hotel.',
                ], 400);
            }

            // Asigna el valor predeterminado para 'estado' como 'activo'
            $validatedData['estado'] = 'ACTIVO';

            // Crear la habitacion en la base de datos
            $habitacion = Habitacion::create($validatedData);

            return response()->json([
                'message' => 'habitacion creada correctamente',
                'data' => $habitacion
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Actualización de la habitacion.
     *
     * Busca la habitacion por el id. 
     * valida los datos de entrada
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con los datos dla habitacion.
     * 
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON con un mensaje de éxito 
     *                                       si la habitacion se actualiza correctamente o un mensaje 
     *                                       de error en caso de que falle.
    */

    public function update(Request $request, $id)
    {
        try {
            // Buscar la habitacion por ID
            $habitacion = Habitacion::findOrFail($id);

            // Validar los datos entrantes, sin necesidad de 'estado'
            $validatedData = $request->validate([
                'id_hotel' => 'required|numeric',
                'cantidad' => 'required|numeric',
                'tipo_habitacion' => 'required|string',
                'acomodacion' => 'required|string',
            ]);

            // Verificar si ya existe una habitación con el mismo tipo y acomodación para el hotel
            $existeHabitacion = Habitacion::where('id_hotel', $validatedData['id_hotel'])
                ->where('tipo_habitacion', $validatedData['tipo_habitacion'])
                ->where('acomodacion', $validatedData['acomodacion'])
                ->where('estado', '!=', 'ELIMINADO')
                ->where('id', '!=', $id)
                ->exists();

            if ($existeHabitacion) {
                return response()->json([
                    'error' => 'Ya existe una habitación con el mismo tipo y acomodación para este hotel.',
                ], 400);
            }

            // Actualizar los datos dla habitacion
            $habitacion->update($validatedData);

            return response()->json([
                'message' => 'Hotel actualizado correctamente',
                'data' => $habitacion
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Consulta de habitaciones.
     *
     * Consulta el total de habitaciones
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP.
     * 
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON con un los datos de los habitaciones.
    */

    public function index()
    {
        try {
            $habitaciones = Habitacion::where('estado', '!=', 'ELIMINADO')->with('hotel') ->get();

            return response()->json([
                'data' => $habitaciones
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Consulta de hotel.
     *
     * Consulta la habitacion por el id único
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con el id de la habitacion.
     * 
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON con un los datos de la habitacion.
    */

    public function show($id)
    {
        try {
            $habitacion = Habitacion::where('id', $id)
                ->where('estado', '!=', 'ELIMINADO')
                ->firstOrFail();

            return response()->json([
                'data' => $habitacion
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminación de habitacion.
     *
     * Cambia el estado a eliminado de un habitacion por id único
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con el id dla habitacion.
     * 
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON con mensaje de exito o error.
    */

    public function destroy($id)
    {
        try {
            // Buscar la habitacion por ID
            $habitacion = Habitacion::findOrFail($id);

            // Actualizar el estado a "ELIMINADO"
            $habitacion->estado = 'ELIMINADO';
            $habitacion->save();

            return response()->json([
                'message' => 'habitacion eliminada correctamente.',
                'data' => $habitacion
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cantidadHabitaciones($hotel_id)
    {
        
        
        

        try {
            // Verificar si el hotel existe
            $hotel = Hotel::find($hotel_id);

            if (!$hotel) {
                return response()->json(['error' => 'Hotel no encontrado'], 404);
            }

            // Sumar las habitaciones asociadas
            $totalHabitaciones = Habitacion::where('id_hotel', $hotel_id)->sum('cantidad');

            return response()->json(['total' => $totalHabitaciones], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
