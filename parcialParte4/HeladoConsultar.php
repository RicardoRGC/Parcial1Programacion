<?php



include_once("heladeria.php");
$sabor = isset($_POST["sabor"]) ? $_POST["sabor"] : null;
$tipo = isset($_POST["tipo"]) ? $_POST["tipo"] : null;
if ($sabor != null && ($tipo == "agua" || $tipo == "crema")) {

  $helado = new Heladeria();

  $helado->sabor = $sabor;
  $helado->tipo = $tipo;


  $arrayProductos = Heladeria::leerJsonheladeria("heladeria");

  if (Heladeria::ComprobarStock($arrayProductos, $helado)) {
    echo "<br/>Si hay Stock";
  } else {
    echo  "<br/>No hay stock";
  }
} else {
  echo "no ingreso los datos";
}
