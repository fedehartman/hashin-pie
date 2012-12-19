<?php

class AccessController extends IugoController {

    function beforeAction () {
        //CANONICAL REDIRECT
        if ($_SERVER['HTTP_HOST']!='localhost' && 
            substr($_SERVER['HTTP_HOST'],0,3) != 'dev' && 
            substr($_SERVER['HTTP_HOST'],0,3) != 'www') {
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: http://www.'.$_SERVER['HTTP_HOST']
                .$_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Función para ingresar al administrador. Muestra la vista del login.
     */
    function login(){
        $html = new HTML;
        $tpl  = new IUGOTemplate("admin/access/login.html");

        $tpl->assign("title",TITLE);
        $tpl->assign("title_legend",PROJECT_TITLE);
        $tpl->assign("css_1",$html->includeCss('admin/base'));
        $tpl->assign("css_2",$html->includeCss('admin/themes/'.THEME.'/style'));
        $tpl->assign("css_3",$html->includeCss('admin/custom'));
        $tpl->assign('icon_key',$html->image('admin/icons/key.png','Login'));
        
        
        if (safePostVar('submit'))
        {
            $user = User::getByUsername(safePostVar('username'));
            if (!$user->id)
            {
                Session::instance()->setFlash(MSG_BAD_LOGIN,'error');
                $tpl->assign("msg_error", Session::instance()->getFlash());
                Session::instance()->setFlash("");
            }
            else
            {
                if(comparePassword(safePostVar('password'),$user->clave))
                {
                    Session::instance()->setLoggedUser($user, false, false, false);
                    $usuario_actual=Session::instance()->getLoggedUser();
                    redirectAction("/admin/main/index");                    
                }
                else
                {
                    Session::instance()->setFlash(MSG_BAD_LOGIN,'error');
                    $tpl->assign("msg_error", Session::instance()->getFlash());
                    Session::instance()->setFlash("");
                }
            }
        }

        $tpl->printToScreen();

    }

    /**
     * Función para destruir la sesión del usuario logueado
     */
    function logout()
    {
        Session::instance()->destroy();
        redirectAction("/admin/access/login");
    }

    function afterAction() {

    }

}
