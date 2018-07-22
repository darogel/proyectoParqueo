<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Administrador;
use \App\Models\Parqueadero;

/**
 * Description of ParqueaderoController
 *
 * @author Darwin
 */
class ParqueaderoController extends Controller {

    //put your code here
    public function registrarParqueadero(Request $request) {
        if ($request->isJson()) {
            $data = $request->json()->all();
            try {
                $admin = Administrador::where("external_id", $data["clave"])->first();
                if ($admin) {
                    $administrador = Administrador::find($admin->id_admin);
                    $parqueadero = new Parqueadero();
                    $parqueadero->nombre = $data["nombre"];
                    $parqueadero->coordenada_x = $data["coordenada_x"];
                    $parqueadero->coordenada_y = $data["coordenada_y"];
                    $parqueadero->precio_hora = $data["precio"];
                    $parqueadero->numero_plazas = $data["plazas"];
                    $parqueadero->external_id = utilidades\UUID::v4();
                    $parqueadero->administrador()->associate($administrador);
                    $parqueadero->save();
                    return response()->json(["mensaje" => "Operacion exitosa", "siglas" => "OE"], 200);
                } else {
                    return response()->json(["mensaje" => "No se ha encontrado ningun dato", "siglas" => "NDE"], 203);
                }
            } catch (Exception $ex) {
                return response()->json(["mensaje" => "Faltan Datos", "siglas" => "FD"], 400);
            }
        } else {
            return response()->json(["mensaje" => "La data no tiene el formato deseado", "siglas" => "DNF"], 404);
        }
    }

    public function modificarParqueadero(Request $request) {

        if ($request->isJson()) {
            $data = $request->json()->all();
            try {
                $parqueaderoObjeto = Parqueadero::where("external_id", $data["external_id"])->first();
                if (isset($data["nombre"])) {
                    $parqueaderoObjeto->nombre = $data["nombre"];
                }
                if (isset($data["coordenada_x"])) {
                    $parqueaderoObjeto->coordenada_x = $data["coordenada_x"];
                }
                if (isset($data["coordenada_y"])) {
                    $parqueaderoObjeto->coordenada_y = $data["coordenada_y"];
                }
                if (isset($data["precio"])) {
                    $parqueaderoObjeto->precio_hora = $data["precio"];
                }
                if (isset($data["plazas"])) {
                    $parqueaderoObjeto->numero_plazas = $data["plazas"];
                }
                $parqueaderoObjeto->save();
                return response()->json(["mensaje" => "Operacion exitosa", "siglas" => "OE"], 200);
            } catch (Exceptio $ex) {
                return response()->json(["mensaje" => "Faltan Datos", "siglas" => "FD"], 400);
            }
        } else {
            return response()->json(["mensaje" => "La data no tiene el formato deseado", "siglas" => "DNF"], 404);
        }
    }

    public function eliminarParqueadero(Request $request) {
        if ($request->isJson()) {
            $data = $request->json()->all();
            try {
                $parqueaderoObjeto = Parqueadero::where("external_id", $data["external_id"])->first();

                $parqueaderoObjeto->estado = 0;
                $parqueaderoObjeto->save();
                return response()->json(["mensaje" => "Operacion exitosa", "siglas" => "OE"], 200);
            } catch (Exceptio $ex) {
                return response()->json(["mensaje" => "Faltan Datos", "siglas" => "FD"], 400);
            }
        } else {
            return response()->json(["mensaje" => "La data no tiene el formato deseado", "siglas" => "DNF"], 404);
        }
    }

}
