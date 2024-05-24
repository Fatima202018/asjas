<?php

use \vista\Vista;
use \App\modelo\Formapago;

class FormaPagoController {

	
    public function index(){
    	$data = Formapago::all();
        return Vista::crear("formaspagos.index",array(
        	"formaspagos" => $data
        ));
    }

}