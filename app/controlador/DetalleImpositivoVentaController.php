<?php
use \vista\Vista;
use \App\modelo\Detalleimpositivoventa;

class DetalleImpositivoVentaController {
	//ruta principal retorna el listado de detalleventas
    public function index(){
    	$data = Detalleimpositivoventa::all();
        return Vista::crear("detalleimpositivoventas.index",array(
        	"detalleimpositivoventas" => $data
        ));
    }
  
}