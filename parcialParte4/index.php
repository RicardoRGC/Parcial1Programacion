<?php


$metodos = $_SERVER['REQUEST_METHOD'];


switch ($metodos) {
    case 'GET':

        $tarea = $_GET["tarea"];
        // var_dump($_POST);
        switch ($tarea) {
            case 'ConsultasVentas':

                include("ConsultasVentas.php");

                break;
            case 'ConsultasDevoluciones':

                include("ConsultasDevoluciones.php");
                break;

            default:
                echo "Error en seleccionar tarea";
                break;
        }
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
            case 'DevolverHelado':

                include("DevolverHelado.php");
                break;

            default:
                # code...
                break;
        }
        break;
    case 'PUT':

        include("ModificarVenta.php");

        break;
    case 'DELETE':

        include("borrarVenta.php");

        break;

    default:
        # code...
        break;
}