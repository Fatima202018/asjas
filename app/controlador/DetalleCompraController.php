<?php
use \vista\Vista;
use \App\modelo\Detallecompra;

class DetalleCompraController {
	//ruta principal retorna el listado de detallecompras
    public function index(){
    	$data = Detallecompra::all();
        return Vista::crear("detallecompras.index",array(
        	"detallecompras" => $data
        ));
    }

}