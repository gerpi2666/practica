<?php
/* Mostrar errores */
    ini_set('display_errors',1);
    ini_set("log_errors",1);
    ini_set("error_log","C:/xampp/htdocs/practica1/php_error_log");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");
    /* Requerimientos */
require_once "models/MySQLConnect.php";

//Agregar todos los modelos
require_once "models/CategoriaModel.php";
require_once "models/TallaModel.php";
require_once "models/UsuarioModel.php";
require_once "models/BicicletaModel.php";
require_once "models/AlquilerModel.php";


//Agregar todos los controladores
require_once "controllers/BicicletaController.php";
require_once "controllers/AlquilerController.php";
    
    require_once "controllers/RoutesController.php";
    $index=new RoutesController();
    $index->index();

