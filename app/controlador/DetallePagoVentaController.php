<?php
use \vista\Vista;
use \App\modelo\Detallepagoventa;

class DetallePagoVentaController {
	//ruta principal retorna el listado de pagos de ventas
    public function index(){
    	$data = Detallepagoventa::all();
        return Vista::crear("detallepagosventas.index",array(
        	"detallepagosventas" => $data
        ));
    }

}