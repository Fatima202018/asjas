<?php
use \vista\Vista;
use \App\modelo\Categoria;

class CategoriaController {	
    public function index(){
    	$data = Categoria::all();
        return Vista::crear("categorias.index",array(
        	"categorias" => $data
        ));
    }

    public function crear(){
        return Vista::crear("categorias.crear");
    }

    public function crearcategoria(){
    	$categoria = new Categoria();
    	$categoria->descripcion = input("txtDescripcion");
		
    	$categoria->guardar();

    	redireccionar("/categorias");
    }

    public function eliminar(){
    	$categoria = Categoria::find(input("id"));
    	$categoria->eliminar();
		
    	redireccionar("/categorias");
    }
        
    public function editar(){
    	$categoria = Categoria::find(input("id"));
    	return Vista::crear("categorias.editar",array(
    		"categoria" => $categoria
    	));
    }
 
    public function editarcategoria(){
    	$categoria = Categoria::find(input("id"));
    	$categoria->descripcion = input("txtDescripcion");
    	$categoria->guardar();

    	redireccionar("/categorias");
    }
            
     public function listado() {
        $categorias = Categoria::all();
            foreach($categorias as $categoria) {
                echo $categoria->descripcion."<br>";
            }
    }
        
     public function busqueda(){
       $idcat = $_REQUEST["id"];
       $categoria = Categoria::find($idcat);
       echo $categoria->descripcion;
   }
    
     public function cantidad(){
        $categoria = Categoria::all();
        $cantidad = count($categoria);        
        echo $cantidad;    
     }                           
}