<?php


class Heladeria
{
    //(sabor, precio,mai
    public $sabor;
    public int $precio;
    public $tipo;
    public int $stock;
    public $id;




    public function __construct($sabor = "", $precio = 0, $tipo = "", $stock = 0, $id = 0)
    {
        $this->sabor = $sabor;
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->stock = $stock;
        $this->id = $id;
    }
    public function Setstock($valor)
    {
        $this->stock += $valor;
    }
    public function SetPrecio($valor)
    {
        $this->precio = $valor;
    }
    public function SetstockDescontar($valor)
    {
        $this->stock -= $valor;
    }
    public function getPrecio()
    {

        return $this->precio;
    }
    public function Getstock()
    {

        return $this->stock;
    }
    public function GetTipo()
    {

        return $this->tipo;
    }

    public function Getsabor()
    {
        return $this->sabor;
    }
    public function GetId()
    {
        return $this->id;
    }
    //--------------------------------------------------------------------------------------------------
    public static function DescontarStock($stockADescontar, $producto, $array)
    {

        if (Heladeria::Equals($array, $producto)) {


            foreach ($array as $value) {

                if (
                    $producto->Getsabor() == $value->Getsabor()
                    && $producto->GetTipo() == $value->GetTipo()
                ) {

                    if ($stockADescontar <= $value->Getstock()) {

                        echo "<br/>  stock " . $value->Getstock() . " ";
                        echo "<br/>  se descuenta al stock :" . $stockADescontar . " ";

                        $value->SetstockDescontar($stockADescontar);

                        echo "<br/> stock Total " . $value->Getstock() . " <br/>";
                        echo "<br/>  Se desconto Correctamente del stock ";

                        echo "<br/>" . " <br/>";

                        // $value->MostrarHelado();
                        return $value;
                    } else {
                        echo "<br/> no hay stock";
                        return null;
                    }
                }
            }
        } else {
            echo "<br/>este producto no se encuentra";
        }

        return null;
    }
    public static function buscarHelado($sabor, $tipo, $stock, $array)
    {

        foreach ($array as $value) {

            if (
                $sabor == $value->Getsabor()
                && $tipo == $value->GetTipo()
            ) {

                if ($stock <= $value->Getstock()) {

                    // $value->MostrarHelado();
                    return $value;
                } else {
                    echo "<br/> no hay stock";
                    return null;
                }
            }
        }

        return null;
    }
    //------------------------------------------------------------------------------------------------------
    public static function BuscarPrecioHelado($sabor, $tipo, $stock, $array)
    {

        foreach ($array as $value) {

            if (
                $sabor == $value->Getsabor()
                && $tipo == $value->GetTipo()
            ) {

                if ($stock <= $value->Getstock()) {

                    $value->MostrarHelado();
                    return $value->getPrecio();
                } else {
                    echo "<br/> no hay stock";
                    return null;
                }
            }
        }

        return null;
    }
    //------------------------------------------------------------------------------------------------------
    public static function agregarHeladeriaJson($Heladeria, $nombreArchivo)
    {
        $json = Heladeria::convertirArrayAJson($Heladeria);

        $bytes = file_put_contents("$nombreArchivo.json", $json);


        if ($bytes > 5) {
            return true;
        }
    }

    private static function convertirArrayAJson($array)
    {
        $arrayJson = [];
        foreach ($array as $value) {


            array_push($arrayJson, get_object_vars($value));
        }



        return json_encode($arrayJson);
    }
    //-------------------------------------------------------------------------------------------------------------

    public static function ComprobarStock($array, $producto)
    {
        foreach ($array as $value) {

            if ($producto->Getsabor() == $value->Getsabor() && $producto->GetTipo() == $value->GetTipo()) {
                return true;
            }

            if ($producto->Getsabor() == $value->Getsabor()) {
                if ($producto->GetTipo() != $value->GetTipo()) {
                    echo "<br/> no hay el tipo que precisa";
                }
            }
            if ($producto->GetTipo() == $value->GetTipo()) {

                if ($producto->Getsabor() != $value->Getsabor()) {
                    echo "<br/> no hay el sabor que precisa";
                }
            }
        }
        return false;
    }
    public static function Equals($array, $producto)
    {
        foreach ($array as $value) {

            if ($producto->Getsabor() == $value->Getsabor() && $producto->GetTipo() == $value->GetTipo()) {
                return true;
            }
        }
        return false;
    }
    public static function pushArray($array, $producto)
    {
        $arrayAux = $array;
        if (!Heladeria::Equals($arrayAux, $producto)) {


            array_push($arrayAux, $producto);


            echo "se agrego correctamente al array";

            return $arrayAux;
        } else {

            return $arrayAux = Heladeria::ActualizoStock($producto, $arrayAux);
        }
    }

    private static function ActualizoStock($producto, $array)
    {
        foreach ($array as $value) {

            if ($producto->Getsabor() == $value->Getsabor()) {

                echo " stock " . $value->Getstock() . " ";
                echo " se agregara al stock :" . $producto->Getstock() . " ";

                $value->Setstock($producto->Getstock());
                $value->SetPrecio($producto->getPrecio());

                echo "<br/> stock Total " . $value->Getstock() . " <br/>";
                echo " producto modificado ";
                echo "<br/>" . " <br/>";
                return $array;
            }
        }

        return $array;
    }
    //-------------------------------------------------------------------------

    public static function UltimoId()
    {
        $lectura = false;
        if (file_exists("ultimoId.txt")) {

            $archivo = fopen("ultimoId.txt", "r");

            $lectura = fread($archivo, filesize("ultimoId.txt"));
            fclose($archivo);
        }

        if (!$lectura) {
            $lectura = 1;
            echo "se creo archivo ID autoincrementable";
        } else {
            $lectura++;
        }

        Heladeria::GuardarUltimoId($lectura);

        return $lectura;
    }
    public static function GuardarUltimoId($id)
    {

        $archivo = fopen("ultimoId.txt", "w");

        fwrite($archivo, "$id");

        fclose($archivo);
    }


    public static function leerJsonheladeria($nombreArchivo)
    {
        $arrayHeladeria = [];


        if (!file_exists("$nombreArchivo.json")) {
            echo "<br/>El archivo $nombreArchivo.json  no existe. Verifique!!!";
            return $arrayHeladeria;
        }

        $data = file_get_contents("$nombreArchivo.json");
        if (!$data) {
            return $arrayHeladeria;
        }
        $products = json_decode($data, true);
        if ($products) {

            $tam = count($products);



            foreach ($products as $value) {


                array_push(
                    $arrayHeladeria,
                    new Heladeria($value['sabor'], $value['precio'], $value['tipo'], $value['stock'], $value['id'])
                );
            }

            return $arrayHeladeria;
        }
    }

    public function MostrarHelado()
    {
        echo "<br/> sabor: " . $this->sabor . " tipo: " . $this->tipo . " stock " . $this->stock . " precio " . $this->precio . "<br/>";
    }
}
