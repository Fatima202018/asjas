<?php
use \vista\Vista;
use \App\modelo\Flujodecaja; 

class FlujodeCajaController {

    public function index(){
		$data = Flujodecaja::all();
        return Vista::crear("flujodecajas.index",array(
        	"flujodecajas" => $data
        ));
    }
    
}

