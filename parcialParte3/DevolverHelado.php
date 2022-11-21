<?php
include("ClaseDevolucion.php");
include("Venta.php");
include("ClaseCupon.php");
//recibo los datos por post.
$numeroPedido = isset($_POST["numeroPedido"]) ? (int)$_POST["numeroPedido"] : 0;
$causa = isset($_POST["causa"]) ? $_POST["causa"] : null;

$tmp_name = isset($_FILES["clienteEnojado"]['tmp_name']) ? $_FILES["clienteEnojado"]['tmp_name'] : NULL;
$destino = isset($_FILES["clienteEnojado"]['name']) ? $_FILES["clienteEnojado"]['name'] : NULL;
$ext = pathinfo($destino, PATHINFO_EXTENSION);
//-------------------------------------------------------------------------------
//destino de la imagen con nombre :numeroPedido y causa.
$destino = "ImagenesDevolucion/" . $numeroPedido . "-" . $causa . "." . $ext;

$arrayDevolucion = Devolucion::leerJsonDevolucion("devoluciones");
$arrayVentas = Venta::leerJsonVenta("ventasJson");

if (Venta::ExisteNumeroPedido($arrayVentas, $numeroPedido)) {

    $devoolucion = new Devolucion($causa, $numeroPedido, $destino); //creo la devolucion

    if (Devolucion::AgregoAlArray($arrayDevolucion, $devoolucion) && Devolucion::guardarArrayEnJson($arrayDevolucion, "devoluciones")) {
        Devolucion::GuardarImagenVenta($destino, $tmp_name, "ImagenesDevolucion");

        echo"<br/>Se realizo una Devolucion";
        //creo cupon de descuento.
        $cupon=new Cupon(Cupon::UltimoId(),$numeroPedido,10);

        $arrayCupones= Cupon::leerJsonCupon("cupones");

        cupon::AgregoAlArray($arrayCupones,$cupon);

        Cupon::guardarArrayEnJson($arrayCupones,"cupones");
    }

}
else {
    echo "no existe el pedido";
}