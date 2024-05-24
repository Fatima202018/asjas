<?php
use \vista\Vista;
    class AdminController {
        public function index() {
            return vista::crear("admin.index");
        }        
        public function error() {
            return vista::crear("admin.error");
        }        
        public function backup() {
            return vista::crear("admin.backup");
        }
    }