<?php
use \vista\Vista;
use \App\modelo\Unidad;

class UnidadController {

	//ruta principal retorna el listado de unidades
    public function index(){
    	$data = Unidad::all();
        return Vista::crear("unidades.index",array(
        	"unidades" => $data
        ));
    }

    public function crear(){
        return Vista::crear("unidades.crear");
    }

    public function crearunidad(){
		try{
			$unidad = new Unidad();
			$unidad->descripcion = input("txtDescripcion");
			
			$unidad->guardar();
			redireccionar("/unidades");	
		} catch (Exception $e) {
            echo $e->getMessage();
        }
    }
	
    public function eliminar(){
    	$unidad = Unidad::find(input("id"));
    	$unidad->eliminar();

    	redireccionar("/unidades");
    }

    public function editar(){
    	$unidad = Unidad::find(input("id"));
    	return Vista::crear("unidades.editar",array(
    		"unidad" => $unidad
    	));
    }

    public function editarunidad(){		
		$unidad = Unidad::find(input("id"));
		$unidad->descripcion = input("txtDescripcion");

		$unidad->guardar();
		redireccionar("/unidades");					
    }
        
    //genera un listado de la tabla unidades y muestra la descripcion de c/u    
     public function listado() {
        $unidades = Unidad::all();
            foreach($unidades as $unidad) {
                echo $unidad->descripcion."<br>";
            }
    }
    
    //metodo que busca la descripcion en la tabla unidades
     public function busqueda(){
       $idMarca = $_REQUEST["id"];
       $unidad = Marca::find($idMarca);
       echo $unidad->descripcion;
   }
    
     public function cantidad(){
        $unidad = Unidad::all();
        $cantidad = count($unidad);        
        echo $cantidad;    
     }
                                
}