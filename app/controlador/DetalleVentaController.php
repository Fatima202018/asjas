<?php
use \vista\Vista;
use \App\modelo\Detalleventa;

class DetalleVentaController {

	//ruta principal retorna el listado de detalleventas
    public function index(){
    	$data = Detalleventa::all();
        return Vista::crear("detalleventas.index",array(
        	"detalleventas" => $data
        ));
    }
  
}