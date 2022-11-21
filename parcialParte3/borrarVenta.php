<?php
include_once("Venta.php");

 $numeroPedido = isset($_REQUEST["numeroPedido"]) ? (int)$_REQUEST["numeroPedido"] : 0;


if($numeroPedido >= 0)
{

    $arrayVentas = Venta::leerJsonVenta("ventasJson");
    $pedido=Venta::ExisteNumeroPedido($arrayVentas, $numeroPedido);
    if ($pedido != null) {

        Venta::BorrarVentaPorNumeroPedido($arrayVentas, $numeroPedido);

        Venta::guardarJsonVenta($arrayVentas,"ventasJson");

        if(!Venta::EstadoDePedido($arrayVentas,$numeroPedido))
        {
            Venta::MoverArchivoBackup($pedido->GetImagen(),"ImagenesBackupVentas");

        }

    }
    else{
        echo "No existe Numero de Pedido O esta dado de baja";
    }
    
}else{
    echo "Error Numero de Pedido";
}
?>