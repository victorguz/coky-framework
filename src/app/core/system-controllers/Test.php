<?php

/**
 * Test.php
 */

namespace PiecesPHP\Core;

use \Slim\Http\Request as Request;
use \Slim\Http\Response as Response;

/**
 * Test.
 *
 * Pruebas.
 *
 * @package     App\Controller
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2018
 */
class Test
{
    /** @ignore */
    public function __construct()
    {
    }

    public function generateImage(Request $request, Response $response, array $args)
    {
        if (isset($args['w']) && isset($args['h'])) {

            $ancho = $args['w'];
            $alto = $args['h'];

            $imagen = imagecreate($ancho, $alto);
            $color_gris = imagecolorallocate($imagen, 183, 183, 183);
            $color_negro = imagecolorallocate($imagen, 0, 0, 0);
            imagefill($imagen, 0, 0, $color_gris);

            $texto = $ancho . "x" . $alto;
            $font = realpath(__DIR__ . "/../system-views/Oswald-ExtraLight.ttf");

            $font_size = imagesx($imagen) * 0.05;
            $x = imagesx($imagen) / 2.6;
            $y = (imagesy($imagen) / 2);
            $angle = 0;
            $text_imagen = imagettftext($imagen, $font_size, $angle, $x, $y, $color_negro, $font, $texto);
            imagejpeg($imagen);
            imagedestroy($imagen);

            return $response->withHeader('Content-type', 'image/jpg');
        } else {
            return $response->withStatus(404)->write('<h1>Recurso inexistente.</h1>');
        }
    }

}
