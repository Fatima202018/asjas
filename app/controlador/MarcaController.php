<?php

use \vista\Vista;
use \App\modelo\Marca;

class MarcaController {

	//ruta principal retorna el listado de marcas
    public function index(){
    	$data = Marca::all();
        return Vista::crear("marcas.index",array(
        	"marcas" => $data
        ));
    }

    public function crear(){
        return Vista::crear("marcas.crear");
    }

    public function crearmarca(){
    	$marca = new Marca();
    	$marca->descripcion = input("txtDescripcion");
    	$marca->guardar();

    	redireccionar("/marcas");
    }
	
	public function eliminar(){
    	$marca = Marca::find(input("id"));
    	$marca->eliminar();

    	redireccionar("/marcas");
    }

    public function editar(){
    	$marca = Marca::find(input("id"));
    	
    	return Vista::crear("marcas.editar",array(
    		"marca" => $marca
    	));
    }
 
    public function editarmarca(){
    	$marca = Marca::find(input("id"));
    	$marca->descripcion = input("txtDescripcion");
    	$marca->guardar();

    	redireccionar("/marcas");
    }
        
    //genera un listado de la tabla marcas y muestra la descripcion de c/u    
     public function listado() {
        $marcas = Marca::all();
            foreach($marcas as $marca) {
                echo $marca->descripcion."<br>";
            }
    }
    
    //metodo que busca la descripcion en la tabla marcas
     public function busqueda(){
       $idMarca = $_REQUEST["id"];
       $marca = Marca::find($idMarca);
       echo $marca->descripcion;
   }
    
     public function cantidad(){
        $marca = Marca::all();
        $cantidad = count($marca);        
        echo $cantidad;    
     }
                                
}