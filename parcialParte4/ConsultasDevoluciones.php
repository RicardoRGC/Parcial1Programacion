<?php
include("ClaseDevolucion.php");
include("Venta.php");
include("ClaseCupon.php");

$ConsultasDevoluciones = isset($_GET["ConsultasDevoluciones"]) ? $_GET["ConsultasDevoluciones"] : null;

$arrayDevolucion = Devolucion::leerJsonDevolucion("devoluciones");
$arrayCupones = Cupon::leerJsonCupon("cupones");

switch ($ConsultasDevoluciones) {
    case 'a':
        # code...
        foreach ($arrayCupones as $key => $cupones) {

            foreach ($arrayDevolucion as $key => $devoluciones) {

                if ($devoluciones->GetnumeroPedido() == $cupones->GetnumeroPedido()) {

                    echo $devoluciones->ToString();
                    echo $cupones->ToString();
                }
            }

        }
        break;

    case 'b':
        foreach ($arrayCupones as $key => $cupones) {

            echo $cupones->ToString();

        }
        break;

    case 'c':
        # code...
        foreach ($arrayCupones as $key => $cupones) {

            foreach ($arrayDevolucion as $key => $devoluciones) {

                if ($devoluciones->GetnumeroPedido() == $cupones->GetnumeroPedido()) {
                    if ($cupones->GetEstado() == "usado") {
                        echo $devoluciones->ToString();
                        echo $cupones->ToString();

                    }
                }


            }

        }
        break;

    default:
        # code...
        break;
}




?>