<?php

require_once("heladeria.php");


class ManejadorArchivos
{


    public static function GuardarImagenPHP($destino, $tmp_name)
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
            //VERIFICO EL TAMA�O MAXIMO QUE PERMITO SUBIR
            if ($_FILES["archivo"]["size"] > 5000000) {
                echo "<br/>El archivo es demasiado grande. Verifique!!!";
                $uploadOk = FALSE;
            }
            //VERIFICO SI ES UNA IMAGEN O NO
//var_dump(getimagesize($_FILES["archivo"]["tmp_name"]));die();

            //OBTIENE EL TAMA�O DE UNA IMAGEN, SI EL ARCHIVO NO ES UNA
//IMAGEN, RETORNA FALSE
            $esImagen = getimagesize($tmp_name);

            if ($esImagen === FALSE) { //NO ES UNA IMAGEN

                //SOLO PERMITO CIERTAS EXTENSIONES
                if ($tipoArchivo != "doc" && $tipoArchivo != "txt" && $tipoArchivo != "rar") {
                    echo "<br/>Solo son permitidos archivos con extension DOC, TXT o RAR.";
                    $uploadOk = FALSE;
                }
            }
            else { // ES UNA IMAGEN

                //SOLO PERMITO CIERTAS EXTENSIONES
                if ($tipoArchivo != "jpg" && $tipoArchivo != "jpeg" && $tipoArchivo != "gif"
                && $tipoArchivo != "png") {
                    echo "<br/>Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.";
                    $uploadOk = FALSE;
                }

            }

            //VERIFICO SI HUBO ALGUN ERROR, CHEQUEANDO $uploadOk
            if ($uploadOk === FALSE) {

                echo "<br/>NO SE PUDO SUBIR EL ARCHIVO.";

            }
            else {
                //MUEVO EL ARCHIVO DEL TEMPORAL AL DESTINO FINAL
                if (move_uploaded_file($tmp_name, $destino)) {
                    echo "<br/>El archivo " . $destino . " ha sido subido exitosamente.";
                }
                else {
                    echo "<br/>Lamentablemente ocurri&oacute; un error y no se pudo subir el archivo.";
                }
            }
        }
        catch (Exception $th) {
            echo '¡Ocurrió un error! ', $th->getMessage();
        }
    }

}


?>