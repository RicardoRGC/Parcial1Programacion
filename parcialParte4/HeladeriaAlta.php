<?php


include_once("heladeria.php");
include_once("ManejadorArchivo.php");

$sabor = isset($_POST["sabor"]) ? $_POST["sabor"] : null;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null;
$precio = isset($_POST["precio"]) ? (int)$_POST["precio"] : 0;
$stock = isset($_POST["stock"]) ? (int)$_POST["stock"] : 0;

$tmp_name = isset($_FILES["archivo"]['tmp_name']) ? $_FILES["archivo"]['tmp_name'] : NULL;
$name =  isset($_FILES["archivo"]['name']) ? $_FILES["archivo"]['name'] : NULL;
if ($name) {

    $destino = "archivos/" . $_FILES["archivo"]["name"];
    $ext = pathinfo($destino, PATHINFO_EXTENSION);
    $destino = "ImagenesDeHelados/" . $tipo . "-" . $sabor . "." . $ext;
}



if ($sabor != null && $precio != 0 && ($tipo == "agua" || $tipo == "crema") && $stock != 0) {

    $pizza = new Heladeria($sabor, $precio, $tipo, $stock, Heladeria::UltimoId());


    $arrayProductos = Heladeria::leerJsonheladeria("heladeria");

    $arrayProductos = Heladeria::pushArray($arrayProductos, $pizza);

    if (!is_null($tmp_name)) {
        ManejadorArchivos::GuardarImagenPHP($destino, $tmp_name);
    } else {
        echo " No ingreso Imagen ";
    }

    Heladeria::agregarHeladeriaJson($arrayProductos, "heladeria");
} else {
    echo "Ingrese los datos correctos";
}
