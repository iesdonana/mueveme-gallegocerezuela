<?php

namespace app\helpers;

use Yii;

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

    public static function noticia($noticia_id)
    {
        if ($imagen = preg_grep("/^{$noticia_id}/", $imagen = self::listaFicheros(Yii::getAlias('@uploads/.')))) {
            return array_values($imagen)[0];
        }
        return false;
    }
}
