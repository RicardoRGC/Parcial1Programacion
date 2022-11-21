<?php
include("ClaseDevolucion.php");
include("Venta.php");
include("ClaseCupon.php");
//recibo los datos por post.
$numeroPedido = isset($_POST["numeroPedido"]) ? (int)$_POST["numeroPedido"] : 0;
$causa = isset($_POST["causa"]) ? $_POST["causa"] : null;

$tmp_name = isset($_FILES["clienteEnojado"]['tmp_name']) ? $_FILES["clienteEnojado"]['tmp_name'] : NULL;

$name =  isset($_FILES["clienteEnojado"]['name']) ? $_FILES["clienteEnojado"]['name'] : NULL;


if (!is_null($numeroPedido) && !is_null($causa) && $causa != "") {
    $destino = "no ingreso imagen";
    if ($name) {

        $destino = isset($_FILES["clienteEnojado"]['name']) ? $_FILES["clienteEnojado"]['name'] : NULL;
        $ext = pathinfo($destino, PATHINFO_EXTENSION);
        //-------------------------------------------------------------------------------
        //destino de la imagen con nombre :numeroPedido y causa.
        $destino = "ImagenesDevolucion/" . $numeroPedido . "-" . $causa . "." . $ext;
    }

    $arrayDevolucion = Devolucion::leerJsonDevolucion("devoluciones");
    $arrayVentas = Venta::leerJsonVenta("ventasJson");

    if (Venta::ExisteNumeroPedido($arrayVentas, $numeroPedido)) {

        $devoolucion = new Devolucion($causa, $numeroPedido, $destino); //creo la devolucion

        if (Devolucion::AgregoAlArray($arrayDevolucion, $devoolucion) && Devolucion::guardarArrayEnJson($arrayDevolucion, "devoluciones")) {
            if ($name) {
                Devolucion::GuardarImagenVenta($destino, $tmp_name, "ImagenesDevolucion");
            }

            echo "<br/>Se realizo una Devolucion";
            //creo cupon de descuento.
            $cupon = new Cupon(Cupon::UltimoId(), $numeroPedido, 10);

            $arrayCupones = Cupon::leerJsonCupon("cupones");

            Venta::BorrarVentaPorNumeroPedido($arrayVentas, $numeroPedido);
            cupon::AgregoAlArray($arrayCupones, $cupon);

            Venta::guardarJsonVenta($arrayVentas, "ventasJson");

            Cupon::guardarArrayEnJson($arrayCupones, "cupones");
        }
    } else {
        echo "no existe el pedido, Ingrese numeroPedido,causa,clienteEnojado";
    }
} else {
    echo "Error ingrese: causa,numeroPedido";
}
