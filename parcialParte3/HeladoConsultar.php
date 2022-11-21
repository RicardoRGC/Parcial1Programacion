<?php



include_once("heladeria.php");

$helado=new Heladeria($_POST["sabor"],0,$_POST["tipo"],0,0);

$arrayProductos=Heladeria::leerJsonheladeria("heladeria");

if(Heladeria::ComprobarStock($arrayProductos,$helado ))
{
    echo "<br/>si hay";
}
else{
  echo  "<br/>no hay";
}

