<?php
use \App\modelo\Reporte; 
use \vista\Vista;

class ReporteController {

    public function index(){
        return Vista::crear("reportes.index");
    }
       
    public function reportes(){
        return Vista::crear("reportes.reportes");
    }
}