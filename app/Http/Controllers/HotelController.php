<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Creación del hotel.
     *
     * Valida los datos de entrada. 
     * Realiza las siguientes acciones:
     *  - Crea el elemento en la base de datos.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con los datos del hotel.
     * 
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON con un mensaje de éxito 
     *                                       si el hotel se crea correctamente o un mensaje 
     *                                       de error en caso de que falle.
    */

    public function store(Request $request) {

        try {

            //Valida los datos de entrada
            $validatedData = $request->validate([
                'nombre' => 'required|string|unique:hotel,nombre',
                'direccion' => 'required|string',
                'ciudad' => 'required|string',
                'nit' => 'required|numeric|unique:hotel,nit',
                'digito_verificacion' => 'required|numeric',
                'numero_habitaciones' => 'required|numeric',
            ]);

            // Asigna el valor predeterminado para 'estado' como 'activo'
            $validatedData['estado'] = 'ACTIVO';

            // Crear el hotel en la base de datos
            $hotel = Hotel::create($validatedData);

            return response()->json([
                'message' => 'Hotel creado correctamente',
                'data' => $hotel
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Actualización del hotel.
     *
     * Busca el hotel por el id. 
     * valida los datos de entrada
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con los datos del hotel.
     * 
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON con un mensaje de éxito 
     *                                       si el hotel se actualiza correctamente o un mensaje 
     *                                       de error en caso de que falle.
    */

    public function update(Request $request, $id)
    {
        try {
            // Buscar el hotel por ID
            $hotel = Hotel::findOrFail($id);

            // Validar los datos entrantes, sin necesidad de 'estado'
            $validatedData = $request->validate([
                'nombre' => 'required|string|unique:hotel,nombre,' . $id,
                'direccion' => 'required|string',
                'ciudad' => 'required|string',
                'nit' => 'required|numeric|unique:hotel,nit,' . $id,
                'digito_verificacion' => 'required|numeric',
                'numero_habitaciones' => 'required|numeric',
            ]);

            // Actualizar los datos del hotel
            $hotel->update($validatedData);

            return response()->json([
                'message' => 'Hotel actualizado correctamente',
                'data' => $hotel
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Consulta de hoteles.
     *
     * Consulta el total de hoteles
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP.
     * 
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON con un los datos de los hoteles.
    */

    public function index()
    {
        try {
            $hoteles = Hotel::where('estado', '!=', 'ELIMINADO')->get();

            return response()->json([
                'data' => $hoteles
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Consulta de hotel.
     *
     * Consulta el hotel por el id único
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con el id del hotel.
     * 
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON con un los datos del hotel.
    */

    public function show($id)
    {
        try {
            $hotel = Hotel::where('id', $id)
                ->where('estado', '!=', 'ELIMINADO')
                ->firstOrFail();

            return response()->json([
                'data' => $hotel
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Eliminación de hotel.
     *
     * Cambia el estado a eliminado de un hotel por id único
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP con el id del hotel.
     * 
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON con mensaje de exito o error.
    */
    public function destroy($id)
    {
        try {
            // Buscar el hotel por ID
            $hotel = Hotel::findOrFail($id);

            // Actualizar el estado a "ELIMINADO"
            $hotel->estado = 'ELIMINADO';
            $hotel->save();

            return response()->json([
                'message' => 'Hotel eliminado correctamente.',
                'data' => $hotel
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
