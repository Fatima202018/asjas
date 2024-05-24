<?php
use \vista\Vista;
use \App\modelo\Parametro;

class ParametroController {	
    public function index(){
		$parametro = Parametro::find(1);		
        return Vista::crear("parametros.index",array(
        	"parametro" => $parametro
        ));
    }
	
    public function actualizarparametros() {	
        $parametro = Parametro::find(1);
        
        $parametro->nombre_empresa = input("txtNombreEmpresa"); 
        $parametro->rubro = input("txtRubro");
        $parametro->cuit = input("txtCuit");
        $parametro->pais = input("txtPais");
		
		//nuevo
		$parametro->modo_impresion = input("txtModoPrint");
		
        $parametro->domicilio_comercial = input("txtDomicilio");
        $parametro->telefono = input("txtTelefono");
        $parametro->email = input("txtEmail");
		$parametro->nombre_impuesto = strtoupper(input("txtNombreImpuesto"));   
		//$parametro->porcentaje_impuesto = input("txtPorcentajeImpuesto");   
		$parametro->idenTributaria = strtoupper(input("txtIdenTributaria"));   
		$parametro->moneda = input("txtMoneda"); 
		$parametro->puntoVenta = input("txtPuntoVenta");			
		$parametro->ingresos_brutos = input("txtIngBrutos");
		$parametro->fecIniAct = input("txtfecIniAct");						
		//$parametro->condicionIVA = input("txtCondicionIVA");		
		
		//si el pais es distinto de Arg
		if ($parametro->pais != "AR"){
			$parametro->condicionIVA = "NI";
		}else{
			$parametro->condicionIVA = input("txtCondicionIVA");		
		}
		//si es MT Monotributista no aplica alicuota, y va 0
		if($parametro->pais == "AR" && input("txtCondicionIVA") == "MT"){
			$parametro->porcentaje_impuesto = 0.00;			
		}else{			
			$parametro->porcentaje_impuesto = input("txtPorcentajeImpuesto");   	
		}
		
		//Para guardar el logo
		if ($_FILES["txtLogo"]["error"] > 0){
			//echo "ha ocurrido un error";
		} else {
			//ahora vamos a verificar si el tipo de archivo es un tipo de imagen permitido.			
			$permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png");
			//$limite_kb = 100;
			$limite_kb = 1048576; //1 MB			
			//if (in_array($_FILES['txtLogo']['type'], $permitidos) && $_FILES['txtLogo']['size'] <= $limite_kb * 1024){
			if (in_array($_FILES['txtLogo']['type'], $permitidos) && $_FILES['txtLogo']['size'] <= $limite_kb){
				//esta es la ruta donde copiaremos la imagen
				//recuerden que deben crear un directorio con este mismo nombre
				$ruta = "assets/img/" . $_FILES['txtLogo']['name'];
				$resultado = @move_uploaded_file($_FILES["txtLogo"]["tmp_name"], $ruta);
				
				//echo $ruta;
				//echo $resultado;
			} else {
				echo "archivo no permitido o excede el tamano de $limite_kb Kilobytes";
			}
		}		
        $parametro->logo_ruta = $ruta;   
		$parametro->logo_nombre = $_FILES['txtLogo']['name'];   
        
        $parametro->guardar();
        redireccionar("/parametros");
    }
}