<?php
/**
 * Clase que contiene los template para cada vista del controlador.
 *
 * Template para el controlador Main
 */
class TplFrtContainer
{

    function getContainer()
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("front/container.html");

        return $tpl;
    }

}
?>
