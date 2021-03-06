<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservacion;
use App\Models\Usuario;
use App\Models\Parqueadero;
use App\Models\Vehiculo;
use App\Models\Plaza;
use App\Models\Administrador;

/**
 * Description of ReservacionController
 * Clase usada para el control de las funciones de reservaciones
 */
class ReservacionController extends Controller {
    
    /**
     * Función para registrar una reservación
     * @param Request $request
     * @return type mensaje json
     */
    public function registrarReservacion(Request $request) {
        if ($request->isJson()) {
            $data = $request->json()->all();
            try {
                $vehiculo = Vehiculo::where('external_id', $data["idV"])->first();
                $plaza = Plaza::where('external_id', $data["idP"])->first();
                $vehiculo = Vehiculo::find($vehiculo->id_vehiculo);
                $plaza = Plaza::find($plaza->id_plaza);
                $reservacion = new Reservacion();
                $reservacion->hora_entrada = $data["entrada"];
                $reservacion->hora_salida = $data["salida"];
                $reservacion->external_id = utilidades\UUID::v4();
                $reservacion->vehiculo()->associate($vehiculo);
                $reservacion->plaza()->associate($plaza);
                $reservacion->save();
                return response()->json(["mensaje" => "Operacion exitosa", "siglas" => "OE"], 200);
            } catch (Exception $ex) {
                return response()->json(["mensaje" => "Faltan Datos", "siglas" => "FD"], 400);
            }
        } else {
            return response()->json(["mensaje" => "La data no tiene el formato deseado", "siglas" => "DNF"], 404);
        }
    }

    /**
     * Función para modificar una reservación
     * @param Request $request
     * @return type mensaje json
     */
    public function modificarReservacion(Request $request) {

        if ($request->isJson()) {
            $data = $request->json()->all();
            try {
                $reservacionObjeto = Reservacion::where("external_id", $data["external_id"])->first();
                if (isset($data["plaza"])) {
                    $reservacionObjeto->id_plaza = $data["plaza"];
                }
                if (isset($data["entrada"])) {
                    $reservacionObjeto->hora_entrada = $data["entrada"];
                }
                if (isset($data["salida"])) {
                    $reservacionObjeto->hora_salida = $data["salida"];
                }
                $reservacionObjeto->save();
                return response()->json(["mensaje" => "Operacion exitosa", "siglas" => "OE"], 200);
            } catch (Exceptio $ex) {
                return response()->json(["mensaje" => "Faltan Datos", "siglas" => "FD"], 400);
            }
        } else {
            return response()->json(["mensaje" => "La data no tiene el formato deseado", "siglas" => "DNF"], 404);
        }
    }

    /**
     * Función para eliminar una reservación
     * @param Request $request
     * @return type mensaje json
     */
    public function eliminarReservacion(Request $request) {
        if ($request->isJson()) {
            $data = $request->json()->all();
            try {
                $ReservacionObjeto = Reservacion::where("external_id", $data["external_id"])->first();

                $ReservacionObjeto->estado = 0;
                $ReservacionObjeto->save();
                return response()->json(["mensaje" => "Operacion exitosa", "siglas" => "OE"], 200);
            } catch (Exceptio $ex) {
                return response()->json(["mensaje" => "Faltan Datos", "siglas" => "FD"], 400);
            }
        } else {
            return response()->json(["mensaje" => "La data no tiene el formato deseado", "siglas" => "DNF"], 404);
        }
    }
    
    /**
     * Función para listar las reservaciones de los usuarios
     * @param type $external_id
     * @return type array con los datos de las reservaciones del usuario
     */
    public function listarReservacionesUsuario($external_id) {
        $usuario = Usuario::where('external_id', $external_id)->first();
        $lista = Usuario::find($usuario->id_usuario)->reservacion;
        $data = array();
        foreach ($lista as $item) {
            $vehiculo= Vehiculo::where('id_vehiculo',$item->id_vehiculo)->first();
            $data[] = ["vehiculo" => $vehiculo->placa,
                "hora_entrada" => $item->hora_entrada,
                "hora_salida" => $item->hora_salida,
                "fecha" => $item->created_at,
                "external_id"=> $item->external_id];
        }
        return response()->json($data, 200);
    }

}
