<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController{

    public static function index(){
        
        $servicio = Servicio::all();
        echo json_encode($servicio, JSON_UNESCAPED_UNICODE);
    }

    public static function guardar(){

        $cita = new Cita($_POST);

        $resultado = $cita->guardar();

        $id = $resultado['id'];

        $idServicios = explode(",", $_POST['servicios']);

        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];

            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode(['resultado' => $resultado], JSON_UNESCAPED_UNICODE);
    }

    public static function eliminar(){
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $id = $_POST['id'];

            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}