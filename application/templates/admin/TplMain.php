<?php
/**
 * Clase que contiene los template para cada vista del controlador.
 *
 * Template para el controlador Main
 */
class TplMain
{

    /**
     * Funcion que crea un template para la vista index del controlador
     * @return array $tpl devuelve el template de la vista index
     */
    function getIndex()
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("admin/main/index.html");
        $tpl->assign("msg_error", Session::instance()->getAndClearFlash());   
 
        return $tpl;
    }
}
?>
