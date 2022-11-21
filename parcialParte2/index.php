<?php


$metodos = $_SERVER['REQUEST_METHOD'];


switch ($metodos) {
    case 'GET':
       
        include("ConsultasVentas.php");
        
        break;
    case 'POST':

        $tarea = $_POST["tarea"];
        // var_dump($_POST);
        switch ($tarea) {
            case 'heladeriaAlta':
           
                include("HeladeriaAlta.php");
                break;
            case 'HeladoConsultar':
           
                include("HeladoConsultar.php");
                break;
            case 'AltaVenta':
           
                include("AltaVenta.php");
                break;

            default:
                # code...
                break;
        }
        break;
        case 'PUT':
       
            include("ModificarVenta.php");
            
            break;

    default:
        # code...
        break;
}