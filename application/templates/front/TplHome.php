<?php
/**
 * Clase que contiene los template para cada vista del controlador.
 *
 * Template para el controlador Main
 */
class TplHome
{

    function getHome($fondos)
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("front/home/home.html");


        return $tpl;
    }

}
?>
