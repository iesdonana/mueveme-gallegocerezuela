<?php

namespace app\helpers;

/**
 * La funcion de esta clase es sacar el dominio de un sitio.
 */
class DomainExtractor
{
    /**
     * La funcion de esta clase es sacar el dominio a partir de una url.
     * https://github.com/iesdonana/mueveme-gallegocerezuela/ -> github.com.
     * @param  string $url La url de donde se quiere sacar el domínio.
     * @return string      El dominio
     */
    public static function fromUrl($url)
    {
        $protocolos = ['http://', 'https://', 'ftp://', 'www.'];
        $url = explode('/', str_replace($protocolos, '', $url));
        return $url[0];
    }
}
