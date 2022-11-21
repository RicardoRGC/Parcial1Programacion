<?php


include_once("Venta.php");
include_once("ManejadorArchivo.php");


$ConsultasVentas = isset($_GET["ConsultasVentas"]) ? $_GET["ConsultasVentas"] : null;
$sabor = isset($_GET["sabor"]) ? $_GET["sabor"] : null;
$fecha1= isset($_GET["fecha1"]) ? $_GET["fecha1"] : null;
$fecha2= isset($_GET["fecha2"]) ? $_GET["fecha2"] : null;
$mail= isset($_GET["mail"]) ? $_GET["mail"] : null;


switch ($ConsultasVentas) {
    case 'a':// La cantidad de Helados vendidos en un día
        // en particular(se envía por parámetro),
        // si no se pasa fecha, se
       // muestran las del día de ayer
        $fecha_actual = date("d-m-Y");
         
        $arrayVentas = Venta::leerJsonVenta("ventasJson");
        $arrayVentas= Venta::FiltrarVentasPorFecha($arrayVentas,$fecha1);

        $CantidadVentas = Venta::CantidadHeladosVendidos($arrayVentas);
        echo "cantidad de ventas: $CantidadVentas";
        break;

    case 'c':// El listado de ventas entre dos fechas ordenado por nombre.

        if($fecha1!=null && $fecha2!=null )
        {

            $arrayVentas = Venta::leerJsonVenta("ventasJson");
            // var_dump($arrayVentas);
            $arrayFiltrada = Venta::FiltrarVentasPorFecha($arrayVentas, $fecha1, $fecha2);
           
           
            $arrayOrdenada = Venta::Ordenar($arrayFiltrada,$sabor);
    
             Venta::Mostrar($arrayOrdenada);
        }
        else{

            echo "error en fechas";
        }
        # code...
        break;

    case 'b':// El listado de ventas de un usuario ingresado.

        $arrayVentas = Venta::leerJsonVenta("ventasJson");
        $arrayAux=[];
        foreach ($arrayVentas as $key => $value) {
            # code...
            if($value->Getmail()== $mail)
            array_push($arrayAux, $value);
        }
        if(empty($arrayAux))
        {
            echo "usuario inexistente";
            break;
        }

        Venta::Mostrar($arrayAux);
        break;

    case 'd':// El listado de ventas por sabor ingresado.
        $arrayVentas = Venta::leerJsonVenta("ventasJson");
        $arrayAux=[];
        foreach ($arrayVentas as $key => $value) {
            # code...
            if($value->GetSabor()==$sabor)
            array_push($arrayAux, $value);
        }
        if(empty($arrayAux))
        {
            echo "sabor no existe";
            break;
        }

        Venta::Mostrar($arrayAux);
        break;

    default:
        # code...
        break;
}
