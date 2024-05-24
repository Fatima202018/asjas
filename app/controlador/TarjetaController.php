<?php

use \vista\Vista;
use \App\modelo\Tarjeta;

class TarjetaController {

	//ruta principal retorna el listado de tarjetas
    public function index(){
    	$data = Tarjeta::all();
        return Vista::crear("tarjetas.index",array(
        	"tarjetas" => $data
        ));
    }

    public function crear(){
        return Vista::crear("tarjetas.crear");
    }

    public function creartarjeta(){
    	$tarjeta = new Tarjeta();
    	$tarjeta->descripcion = input("txtDescripcion");
    	$tarjeta->guardar();

    	redireccionar("/tarjetas");
    }

    public function eliminar(){
    	$tarjeta = Tarjeta::find(input("id"));
    	$tarjeta->eliminar();

    	redireccionar("/tarjetas");
    }

    public function editar(){
    	$tarjeta = Tarjeta::find(input("id"));
    	
    	return Vista::crear("tarjetas.editar",array(
    		"tarjeta" => $tarjeta
    	));
    }
 
    public function editartarjeta(){
    	$tarjeta = Tarjeta::find(input("id"));
    	$tarjeta->descripcion = input("txtDescripcion");
    	$tarjeta->guardar();

    	redireccionar("/tarjetas");
    }
        
    //genera un listado de la tabla tarjetas y muestra la descripcion de c/u    
     public function listado() {
        $tarjetas = Tarjeta::all();
            foreach($tarjetas as $tarjeta) {
                echo $tarjeta->descripcion."<br>";
            }
    }
    
    //metodo que busca la descripcion en la tabla tarjetas
     public function busqueda(){
       $idTarjeta = $_REQUEST["id"];
       $tarjeta = Tarjeta::find($idTarjeta);
       echo $tarjeta->descripcion;
   }
    
     public function cantidad(){
        $tarjeta = Tarjeta::all();
        $cantidad = count($tarjeta);        
        echo $cantidad;    
     }
                                
}