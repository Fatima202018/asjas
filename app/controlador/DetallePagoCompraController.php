<?php
use \vista\Vista;
use \App\modelo\Detallepagocompra;

class DetallePagoCompraController {

	//ruta principal retorna el listado de pagos de ventas
    public function index(){
    	$data = Detallepagocompra::all();
        return Vista::crear("detallepagoscompras.index",array(
        	"detallepagoscompras" => $data
        ));
    }

}