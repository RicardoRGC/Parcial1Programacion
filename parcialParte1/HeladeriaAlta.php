<?php


include_once("heladeria.php");
include_once("ManejadorArchivo.php");

$sabor = isset($_POST["sabor"]) ? $_POST["sabor"] : null;
$precio = isset($_POST["precio"]) ? (int)$_POST["precio"] : 0;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null;
$stock = isset($_POST["stock"]) ? (int)$_POST["stock"] : 0;

$tmp_name = isset($_FILES["archivo"]['tmp_name']) ? $_FILES["archivo"]['tmp_name'] : NULL;
$destino = "archivos/" . $_FILES["archivo"]["name"];
$ext = pathinfo($destino, PATHINFO_EXTENSION);
$destino = "ImagenesDeHelados/" . $tipo . "-" . $sabor . "." . $ext;


if ($sabor != null && $precio != 0 && ($tipo == "agua" || $tipo == "crema") && $stock != 0) {

    $pizza = new Heladeria($sabor, $precio, $tipo, $stock, Heladeria::UltimoId());
    

     $arrayProductos = Heladeria::leerJsonheladeria("heladeria");

     $arrayProductos = Heladeria::AgregarProductoEnArray($arrayProductos, $pizza);
     
    ManejadorArchivos::GuardarImagenPHP($destino, $tmp_name);

    Heladeria::agregarHeladeriaJson($arrayProductos, "heladeria");
 }
else {
    echo "datos incorrectos";
}
