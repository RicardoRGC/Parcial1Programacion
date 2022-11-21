<?php


include_once("Venta.php");
include_once("ClaseCupon.php");
include_once("heladeria.php");
include_once("ManejadorArchivo.php");
$destino = "";
$sabor = isset($_POST["sabor"]) ? $_POST["sabor"] : null;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null;
$cantidad = isset($_POST["cantidad"]) ? (int)$_POST["cantidad"] : 0;
$mail = isset($_POST["mail"]) ? $_POST["mail"] : null;
// $precio = isset($_POST["precio"]) ? (int)$_POST["precio"] : 0;
$cuponDescuento = isset($_POST["cuponDescuento"]) ? (int)$_POST["cuponDescuento"] : 0;

$tmp_name = isset($_FILES["ventaImagen"]['tmp_name']) ? $_FILES["ventaImagen"]['tmp_name'] : NULL;
$name =  isset($_FILES["ventaImagen"]['name']) ? $_FILES["ventaImagen"]['name'] : NULL;




if ($sabor != null && $mail != null && ($tipo == "agua" || $tipo == "crema") && $cantidad != 0) {
    if ($name) {

        $destino = "archivos/" . $_FILES["ventaImagen"]["name"];
        $ext = pathinfo($destino, PATHINFO_EXTENSION);
        $porciones = explode("@", $mail);
        $destino = "ImagenesDeLaVenta/" . $tipo . "-" . $sabor . "-" . reset($porciones) . "." . $ext;
    } else {
        echo "no ingreso ventaImagen";
    }
    try {
        //code...
        $arrayProductos = Heladeria::leerJsonheladeria("heladeria");
        $arrayCupones = Cupon::leerJsonCupon("cupones");
        $arrayVentas = Venta::leerJsonVenta("ventasJson");

        $venta = Venta::AltaVenta($sabor, $tipo, $cantidad, $mail, $cuponDescuento, $destino, $arrayProductos, $arrayCupones);

        // var_dump($venta);


        if (Venta::AgregoVentaArray($arrayVentas, $venta)) {

            $helado = Heladeria::buscarHelado($sabor, $tipo, $cantidad, $arrayProductos);
            $helado = Heladeria::DescontarStock($cantidad, $helado, $arrayProductos);

            if ($helado != null) {

                if (Venta::guardarJsonVenta($arrayVentas, "ventasJson")) {
                    if ($name)
                        Venta::GuardarImagenVenta($destino, $tmp_name); //guardo la imagen 
                    Heladeria::agregarHeladeriaJson($arrayProductos, "heladeria");
                    echo "<br/> se Realizo la venta Correctamente";
                }
            }
        }
    } catch (\Throwable $th) {
        echo "error en alta";
    }
} else {
    echo "Datos Incorrectos";
}
