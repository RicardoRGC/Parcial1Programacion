<?php


include_once("Venta.php");
include_once("heladeria.php");
include_once("ManejadorArchivo.php");

$sabor = isset($_REQUEST["sabor"]) ? $_REQUEST["sabor"] : null;
$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : null;
$stock = isset($_REQUEST["stock"]) ? (int)$_REQUEST["stock"] : 0;
$mail = isset($_REQUEST["mail"]) ? $_REQUEST["mail"] : null;
$numeroPedido = isset($_REQUEST["numeroPedido"]) ? (int)$_REQUEST["numeroPedido"] : 0;

// var_dump($_REQUEST);
if ($sabor != null && $numeroPedido != 0 && ($tipo == "agua" || $tipo == "crema") && $stock != 0) {

    $arrayVentas = Venta::leerJsonVenta("ventasJson");
    $arrayProductos = Heladeria::leerJsonheladeria("heladeria");
    $venta = new Venta($mail, $stock, $numeroPedido, $sabor, $tipo);

    if (Venta::ModificarVenta($arrayVentas, $venta, $arrayProductos)) {

        Venta::guardarJsonVenta($arrayVentas, "ventasJson");
        Heladeria::agregarHeladeriaJson($arrayProductos, "heladeria");
    } else {
        echo "<br/> Error al modificar ";
    }
} else {
    echo "<br/>error en los datos, Ingrese sabor,tipo,numeroPedido,stock,mail";
}
