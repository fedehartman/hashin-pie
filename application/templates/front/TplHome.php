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

        $tpl->assign('fondo_1',IMAGES_DIR.$fondos[0]['Image']['path']);
        $tpl->assign('fondo_2',IMAGES_DIR.$fondos[1]['Image']['path']);
        $tpl->assign('fondo_3',IMAGES_DIR.$fondos[2]['Image']['path']);

        return $tpl;
    }

}
?>
