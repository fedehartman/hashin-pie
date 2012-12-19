<?php
/**
 * Clase que contiene el "Container" del administrador.Esta plantilla contiene toda la pagina (Header,footer, menu)
 * del administrador y una variable "CONTENT" que es la que va a contener el template expecifico de cada vista.
 *
 * Template del Administrador
 */
class TplContainer
{

    /**
     * Funcion que devuelve las variables del "Container".
     * @param String $selected_menu Menu selected on the top
     * @return array Devuelve las variables para la plantilla "container"
     */
    function getContainer($selected_menu)
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("admin/admin_container.html");
        //variables para el header---------------------------------------------------------------
        $tpl->assign("title",TITLE);
        $tpl->assign("css_style",$html->includeCss('admin/base'));
        $tpl->assign("css_theme",$html->includeCss('admin/themes/'.THEME.'/style'));
        $tpl->assign("css_custom",$html->includeCss('admin/themes/'.THEME.'/custom'));
        $tpl->assign("css_colorbox",$html->includeCss('admin/colorbox/colorbox'));
        $tpl->assign("css_smoothness",$html->includeCss('admin/smoothness/jquery-ui-1.7.2.custom'));

        $tpl->assign("js_jquery",$html->includeJs('views/admin/jquery'));
        $tpl->assign("js_jquery_ui_all",$html->includeJs('views/admin/jquery.ui.all'));
        $tpl->assign("js_jquery_form",$html->includeJs('views/admin/jquery.form'));
        $tpl->assign("js_funciones",$html->includeJs('views/admin/funciones'));
        $tpl->assign("js_livevalidation_min",$html->includeJs('views/admin/livevalidation.min'));
        $tpl->assign("js_scrollTo",$html->includeJs('views/admin/jquery.scrollTo'));
        $tpl->assign("js_localscroll",$html->includeJs('views/admin/jquery.localscroll'));
        $tpl->assign("js_colorbox",$html->includeJs('views/admin/jquery.colorbox-min'));
        $tpl->assign("js_tablednd",$html->includeJs('views/admin/jquery.tablednd_0_5'));
        $tpl->assign("js_tinymce",$html->includeJs('tiny_mce/tiny_mce'));
        
        //funciones del contenido
        $tpl->assign("js_image",$html->includeJs('views/admin/jquery.imgareaselect.min'));
        $tpl->assign("js_container",$html->includeJs('views/admin/container_admin'));

        //menu del login
        $usuario_actual=Session::instance()->getLoggedUser();
        $tpl->assign("proj_title",PROJECT_TITLE);
        $tpl->assign("logout",$html->link('Log Out','admin/access/logout/'));
        $tpl->assign('BASE_PATH',BASE_PATH);

        $tpl->assign(array("usuario"=>logged_user()->nombre,
                           "id_usuario"=>logged_user()->id));

        switch ($selected_menu)
        {
            case 'profile':
                $tpl->assign('active_profile','active');
                break;
            case 'home': default:
                $tpl->assign('active_home','active');
                break;
        }
        
        $tpl->assign("menu_users",$html->link('Users','admin/user/index/','','','submenu tit_submenu'));
        
        $tpl->assign("anio",date("Y"));
                
        return $tpl;
    }

    function getBlankContainer()
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("admin/blank_container.html");
        return $tpl;
    }

}
?>
