<?php


include_once("Venta.php");
include_once("heladeria.php");
include_once("ManejadorArchivo.php");

$sabor = isset($_POST["sabor"]) ? $_POST["sabor"] : null;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null;
$stock = isset($_POST["stock"]) ? (int)$_POST["stock"] : 0;
$mail = isset($_POST["mail"]) ? $_POST["mail"] : null;
$precio = isset($_POST["precio"]) ? (int)$_POST["precio"] : 0;

$tmp_name = isset($_FILES["ventaImagen"]['tmp_name']) ? $_FILES["ventaImagen"]['tmp_name'] : NULL;
$destino = "archivos/" . $_FILES["ventaImagen"]["name"];
$ext = pathinfo($destino, PATHINFO_EXTENSION);
$porciones = explode("@", $mail);
$destino = "ImagenesDeLaVenta/" . $tipo . "-" . $sabor . "-" . reset($porciones) .  "." . $ext;


$helado = new Heladeria($sabor, 0, $tipo, $stock, 0);
$venta = new Venta($mail, $stock, Venta::UltimoId(), $helado->getsabor(), $helado->GetTipo(), $destino, date("d-m-y"));

$arrayProductos = Heladeria::leerJsonheladeria("heladeria");
$arrayVentas = Venta::leerJsonVenta("ventasJson");


if (Venta::AgregoVentaArray($arrayVentas, $venta)) {
    
    $helado = Heladeria::DescontarStock($helado, $arrayProductos);

    if ($helado != null) {

        if (Venta::guardarJsonVenta($arrayVentas, "ventasJson")) {
            echo "se guardo";
        Venta::GuardarImagenVenta($destino, $tmp_name); //guardo la imagen 
        Heladeria::agregarHeladeriaJson($arrayProductos, "heladeria");
        }
    }

}