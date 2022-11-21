<?php


class Venta
{ //--------------------------------------------------------------------------------//
    //--ATRIBUTOS


    private $mail;
    private $cantItems;
    private $numeroPedido;
    private $fecha;
    private $sabor;
    private $tipo;
    private $imagen;
    private bool $estado;
    private $descuento;
    private $precio;

    //--------------------------------------------------------------------------------//
    //--------------------------------------------------------------------------------//
    //--GETTERS Y SETTERS

    public function GetImagen()
    {
        return $this->imagen;
    }
    public function GetEstado()
    {
        return $this->estado;
    }
    public function GetMail()
    {
        return $this->mail;
    }
    public function GetFecha()
    {
        return $this->fecha;
    }
    public function GetCantItems()
    {
        return $this->cantItems;
    }
    public function GetnumeroPedido()
    {
        return $this->numeroPedido;
    }
    public function GetSabor()
    {
        return $this->sabor;
    }
    public function GetTipo()
    {
        return $this->tipo;
    }



    public function SetEstado($valor = false)
    {
        $this->estado = $valor;
    }
    public function SetTipo($valor)
    {
        $this->tipo = $valor;
    }
    public function SetMail($valor)
    {
        $this->mail = $valor;
    }
    public function SetSabor($valor)
    {
        $this->sabor = $valor;
    }
    public function SetFecha($valor)
    {
        $this->fecha = $valor;
    }
    public function SetCantItems($valor)
    {
        $this->cantItems -= $valor;
    }
    public function SetnumeroPedido($valor)
    {
        $this->numeroPedido = $valor;
    }

    //--------------------------------------------------------------------------------//
    //--CONSTRUCTOR
    public function __construct($mail, $cantItems, $numeroPedido, $sabor, $tipo, $image = null, $fecha = null, $precio = 0, $descuento = 0, $estado = true)
    {
        $this->mail = $mail;
        $this->cantItems = $cantItems;
        $this->numeroPedido = $numeroPedido;
        $this->imagen = $image;
        $this->sabor = $sabor;
        $this->tipo = $tipo;
        $this->fecha = $fecha;
        $this->precio = $precio;
        $this->descuento = $descuento;
        $this->estado = $estado;
    }
    //----------------------------------------------------------------------
    public static function AltaVenta($sabor, $tipo, $stock, $mail, $cuponDescuento = 0, $destinoImagenVenta = "", $arrayProductos, $arrayCupones)
    {
        $venta = null;


        $descuento = Cupon::BuscarCuponRetornarDescuento($arrayCupones, $cuponDescuento);

        $heladoPrecio = Heladeria::BuscarPrecioHelado($sabor, $tipo, $stock, $arrayProductos);
        // var_dump($heladoPrecio);

        $precio = Venta::RealizarDescuento($stock, $heladoPrecio, $descuento);

        $venta = new Venta($mail, $stock, Venta::UltimoId(), $sabor, $tipo, $destinoImagenVenta, date("d-m-y"), $precio, $descuento);



        return $venta;
    }
    //----------------------------------------------------------------------


    /**
     * Summary of RealizarDescuento
     * realiza el porsentaje de descuento y devuelve el precio.
     * @param mixed $stock
     * @param mixed $heladoPrecio
     * @param mixed $descuento
     * @return float
     */
    public static function RealizarDescuento($stock, $heladoPrecio, $descuento)
    {
        $precio = $stock * $heladoPrecio;

        return $precio - (($precio * $descuento) / 100);
    }
    public static function EliminarArchivo($path)
    {
        //ELIMINO EL ARCHIVO
        $elimino = unlink($path);

        if ($elimino) {
            echo "<h2> eliminó EXITOSAMENTE </h2><br/>";
        } else {
            echo "<h2> no se pudo ELIMINAR </h2>";
        }
    }

    /**
     * 
     */
    public static function MoverArchivoBackup($path_origen, $carpeta)
    {
        $porciones = explode("/", $path_origen);


        $path_destino = "./$carpeta/" . reset($porciones);
        //------------------------------------------------------------
        if (!file_exists("./$carpeta/")) {
            mkdir("./$carpeta/", 0700);
        }
        //COPIO EN EL ARCHIVO
        $copio = copy($path_origen, $path_destino);

        if ($copio) {
            echo "<h2> copia EXITOSA </h2><br/>";
            Venta::EliminarArchivo($path_origen);
        } else {
            echo "<h2> no se pudo COPIAR </h2>";
        }
    }

    //----------------------------------------------------------------------

    public static function leerJsonVenta($nombreArchivo)
    {
        $archivo = $nombreArchivo . ".json";
        $arrayHelados = [];

        if (!file_exists("$nombreArchivo.json")) {
            // echo "<br/>El archivo  $nombreArchivo.json    no existe. Verifique!!!";
            return $arrayHelados;
        }

        $data = file_get_contents("$nombreArchivo.json");

        if (!$data) {

            return $arrayHelados;
        }
        $products = json_decode($data, true);
        if ($products) {

            $tam = count($products);

            if (!empty($products)) {


                foreach ($products as $value) {


                    array_push(
                        $arrayHelados,
                        new Venta($value['mail'], $value['cantItems'], $value['numeroPedido'], $value['sabor'], $value['tipo'], $value['imagen'], $value['fecha'], $value['precio'], $value['descuento'], $value['estado'])
                    );
                }
            }
        }
        return $arrayHelados;
    }
    //------------------------------------------------------------------------------------------------

    public static function UltimoId()
    {


        $archivo = fopen("ultimoId.txt", "r");

        $lectura = fread($archivo, filesize("ultimoId.txt"));

        if (!$lectura) {
            $lectura = 1;
            echo "entro";
        } else {
            $lectura++;
        }
        fclose($archivo);

        Heladeria::GuardarUltimoId($lectura);

        return $lectura;
    }
    public static function GuardarUltimoId($id)
    {

        $archivo = fopen("ultimoId.txt", "w");

        fwrite($archivo, "$id");

        fclose($archivo);
    }

    //--------------------------------------------------------------------------------//
    //--TOSTRING	
    public static function Mostrar($array)
    {
        foreach ($array as $value) {
            # code...
            echo "<br/>";

            echo $value->ToStringVenta();
        }
    }
    public function ToStringVenta()
    {
        return "Mail " . $this->GetMail() . " - NumeroPedido: " . $this->GetnumeroPedido() . " - Fecha: " . $this->GetFecha() . " - sabor: " . $this->GetSabor() . "\r\n";
    }
    //--------------------------------------------------------------------------------//
    //--------------------------------------------------------------------------------//
    public static function CantidadHeladosVendidos($array)
    {
        $sumaTotal = 0;
        foreach ($array as $value) {
            $sumaTotal += $value->GetCantItems();
        }
        return $sumaTotal;
    }
    //--------------------------------------------------------------------------------//
    public static function Ordenar(&$objDatos)
    {
        usort($objDatos, Venta
            ::object_sorter('sabor', "DES"));

        return $objDatos;
    }
    public static function object_sorter($clave, $orden = null)
    {
        return function ($a, $b) use ($clave, $orden) {
            $result = ($orden == "DESC") ? strnatcmp($b->$clave, $a->$clave) : strnatcmp($a->$clave, $b->$clave);
            return $result;
        };
    }
    //----------------------------------------------------------------------------------------------------
    public static function ModificarVenta($array, $venta, $arrayProductos)
    {
        $helado = Heladeria::buscarHelado($venta->Getsabor(), $venta->GetTipo(), $venta->GetCantItems(), $arrayProductos);
        if (!is_null($helado)) {
            foreach ($array as $key => $value) {

                if ($value->GetnumeroPedido() == $venta->GetnumeroPedido()) {
                    $value->SetMail($venta->GetMail());
                    $value->SetSabor($venta->GetSabor());
                    $value->SetTipo($venta->GetTipo());
                    $value->SetMail($venta->GetCantItems());

                    echo "<br/>Mofificacion Exitosa <br/> ";
                    $helado = Heladeria::DescontarStock($venta->GetCantItems(), $helado, $arrayProductos);
                    return true;
                }
            }
            echo "<br/>no se encuentra NumeroPedido<br/>";
            return false;
        }

        return false;
    }
    //---------------------------------------------------------------------------------------------------
    public static function FiltrarVentasPorFecha($array, $fecha = null, $fecha1 = null)
    {
        $arrayAux = [];
        // var_dump($fecha . $fecha1);
        if ($fecha == null) {
            $fecha_actual = date("d-m-Y");
            $fecha = date("d-m-y", strtotime($fecha_actual . "- 1 days"));
            $fecha1 = date("d-m-y", strtotime($fecha_actual . "- 1 days"));



            foreach ($array as $value) {

                if (strtotime($value->GetFecha()) == strtotime($fecha)) {

                    array_push($arrayAux, $value);
                }
            }
            return $arrayAux;
        }

        if ($fecha1 == null) {
            foreach ($array as $value) {

                if (strtotime($value->GetFecha()) <= strtotime($fecha)) {

                    array_push($arrayAux, $value);
                }
            }
        } else {
            foreach ($array as $value) {


                if (
                    strtotime($value->GetFecha()) >= strtotime($fecha) &&
                    strtotime($value->GetFecha()) <= strtotime($fecha1)
                ) {
                    array_push($arrayAux, $value);
                }
            }
        }

        return $arrayAux;
    }

    //------------------------------------------------------------------------------------------
    public static function AgregoVentaArray(&$array, $venta)
    {
        $arrayAux = $array;
        // var_dump($arrayAux);
        if (!Venta::Equals($array, $venta)) {

            array_push($array, $venta);

            // var_dump($array);

            // echo "<br/> se agrego correctamente al array Push ".$venta->GetMail()." <br/>";

            return true;
        }

        echo "Venta repetida";
        return false;
    }

    public static function Equals($array, $venta) // busco si contiene el mail.

    {
        foreach ($array as $value) {

            if (
                $venta->GetMail() == $value->GetMail()
                && $venta->GetSabor() == $value->GetSabor()
                && $venta->GetCantItems() == $value->GetCantItems()
                && $venta->GetTipo() == $value->GetTipo()
            ) {
                // echo "<br/> se agrego correctamente al array Push ".$venta->GetMail(). $value->GetMail()." <br/>";
                return true;
            }
        }
        return false;
    }
    public static function BorrarVentaPorNumeroPedido(&$array, $numeroPedido) // busco si contiene el mail.

    {
        foreach ($array as $value) {

            if ($numeroPedido == $value->GetnumeroPedido()) {

                $value->SetEstado();
                echo "<br/> Se borro la venta $numeroPedido <br/>";
                return true;
            }
        }
        return false;
    }
    public static function EstadoDePedido(&$array, $numeroPedido) // busco si contiene el mail.

    {
        foreach ($array as $value) {

            if ($numeroPedido == $value->GetnumeroPedido()) {

                return $value->GetEstado();
            }
        }
        return false;
    }
    public static function ExisteNumeroPedido($array, int $numeroPedido) // busco si contiene el mail.

    {


        foreach ($array as $value) {

            if ($numeroPedido == $value->GetnumeroPedido() && $value->GetEstado() == true) {
                echo "<br/> Se encontro Numero de Pedido $numeroPedido----" . $value->GetnumeroPedido() . "<br/>";
                // echo "<br/> se agrego correctamente al array Push ".$venta->GetMail(). $value->GetMail()." <br/>";
                return $value;
            }
        }
        return null;
    }



    //--------------------------------------------------------------------------------//
    public static function guardarJsonVenta($array, $nombreArchivo)
    {
        $json = Venta::convertirArrayAJson($array);

        $bytes = file_put_contents("$nombreArchivo.json", $json);

        if ($bytes > 5) {
            // echo "<br/>------Se guardo correctamente Json   $nombreArchivo.";
            return true;
        } else {
            return false;
        }
    }

    private static function convertirArrayAJson($array)
    {
        $arrayJson = [];
        // var_dump($array);
        foreach ($array as $value) {

            array_push($arrayJson, get_object_vars($value));
        }
        return json_encode($arrayJson);
    }
    //--------------------------------------------------------------------------------//

    public static function GuardarImagenVenta($destino, $tmp_name)
    {
        try {
            //INDICO CUAL SERA EL DESTINO DEL ARCHIVO SUBIDO
            // $destino = "archivos/" . $_FILES["archivo"]["name"];--------------------

            $uploadOk = TRUE;

            //PATHINFO RETORNA UN ARRAY CON INFORMACION DEL PATH
            //RETORNA : NOMBRE DEL DIRECTORIO; NOMBRE DEL ARCHIVO; EXTENSION DEL ARCHIVO

            //PATHINFO_DIRNAME - retorna solo nombre del directorio
            //PATHINFO_BASENAME - retorna solo el nombre del archivo (con la extension)
            //PATHINFO_EXTENSION - retorna solo extension
            //PATHINFO_FILENAME - retorna solo el nombre del archivo (sin la extension)

            //echo var_dump( pathinfo($destino));die();

            $tipoArchivo = pathinfo($destino, PATHINFO_EXTENSION);


            //VERIFICO QUE EL ARCHIVO NO EXISTA
            if (file_exists($destino)) {
                echo "<br/>El archivo ya existe. Verifique!!!";
                $uploadOk = FALSE;
            }
            if (!file_exists("./ImagenesDeLaVenta")) {
                mkdir("./ImagenesDeLaVenta", 0700);
            }

            //VERIFICO EL TAMA�O MAXIMO QUE PERMITO SUBIR
            if ($_FILES["ventaImagen"]["size"] > 5000000) {
                echo "<br/>El ventaImagen es demasiado grande. Verifique!!!";
                $uploadOk = FALSE;
            }
            //VERIFICO SI ES UNA IMAGEN O NO
            //var_dump(getimagesize($_FILES["ventaImagen"]["tmp_name"]));die();

            //OBTIENE EL TAMA�O DE UNA IMAGEN, SI EL ARCHIVO NO ES UNA
            //IMAGEN, RETORNA FALSE
            $esImagen = getimagesize($tmp_name);

            if ($esImagen === FALSE) { //NO ES UNA IMAGEN

                //SOLO PERMITO CIERTAS EXTENSIONES
                if ($tipoArchivo != "doc" && $tipoArchivo != "txt" && $tipoArchivo != "rar") {
                    echo "<br/>Solo son permitidos archivos con extension DOC, TXT o RAR.";
                    $uploadOk = FALSE;
                }
            } else { // ES UNA IMAGEN

                //SOLO PERMITO CIERTAS EXTENSIONES
                if (
                    $tipoArchivo != "jpg" && $tipoArchivo != "jpeg" && $tipoArchivo != "gif"
                    && $tipoArchivo != "png"
                ) {
                    echo "<br/>Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.";
                    $uploadOk = FALSE;
                }
            }

            //VERIFICO SI HUBO ALGUN ERROR, CHEQUEANDO $uploadOk
            if ($uploadOk === FALSE) {

                echo "<br/>NO SE PUDO SUBIR EL ARCHIVO.";
            } else {
                //MUEVO EL ARCHIVO DEL TEMPORAL AL DESTINO FINAL
                if (move_uploaded_file($tmp_name, $destino)) {
                    echo "<br/>El archivo " . $destino . " ha sido subido exitosamente.";
                } else {
                    echo "<br/>Lamentablemente ocurri&oacute; un error y no se pudo subir el archivo.";
                }
            }
        } catch (Exception $th) {
            echo '¡Ocurrió un error! ', $th->getMessage();
        }
    }
}
