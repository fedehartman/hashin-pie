<?php
/**
 * Clase que contiene el "Container" del administrador.Esta plantilla contiene toda la pagina (Header,footer, menu)
 * del administrador y una variable "CONTENT" que es la que va a contener el template expecifico de cada vista.
 *
 * Template del Administrador
 */
class TplPopupContainer
{

    function getContainer()
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("admin/admin_popup_container.html");
        //variables para el header---------------------------------------------------------------
        $tpl->assign("title",TITLE);
        $tpl->assign("css_gral",$html->includeCss('admin/crop'));
        $tpl->assign("css_colorbox",$html->includeCss('admin/colorbox/colorbox'));
        $tpl->assign("js_jquery",$html->includeJs('views/admin/jquery'));
        $tpl->assign("js_container",$html->includeJs('views/admin/container_admin'));
        $tpl->assign("js_image",$html->includeJs('views/admin/jquery.imgareaselect.min'));
        $tpl->assign("js_colorbox",$html->includeJs('views/admin/jquery.colorbox-min'));
        $tpl->assign("js_custom_crop",$html->includeJs('views/admin/popup_crop'));

        //menu del login
        $usuario_actual=Session::instance()->getLoggedUser();
        $tpl->assign("proj_title",PROJECT_TITLE);
        $tpl->assign('BASE_PATH',BASE_PATH);

        return $tpl;
    }
    
    function getClosePopup()
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("admin/admin_popup_close.html");
        //variables para el header---------------------------------------------------------------
        $tpl->assign("title",TITLE);
        return $tpl;
    }



}
?>
