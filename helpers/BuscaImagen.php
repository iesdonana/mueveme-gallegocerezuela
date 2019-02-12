<?php

namespace app\helpers;

/**
 * La funcion de esta clase es sacar el dominio de un sitio.
 */
class BuscaImagen
{
    public static function listaFicheros($url)
    {
        $directorio = opendir($url);
        $listaFicheros = [];
        while ($fichero = readdir($directorio)) {
            if (!is_dir($fichero)) {
                $listaFicheros[] = $fichero;
            }
        }
        return $listaFicheros;
    }
}
