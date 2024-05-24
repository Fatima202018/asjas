<?php
use \vista\Vista;
use \App\modelo\Detalledevolucion;

class DetalleDevolucionController {
	//ruta principal retorna el listado de detalledevoluciones
    public function index(){
    	$data = Detalledevolucion::all();
        return Vista::crear("detalledevoluciones.index",array(
        	"detalledevoluciones" => $data
        ));
    }
  
  
}