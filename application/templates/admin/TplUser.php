<?php
/**
 * Clase que contiene los template para cada vista del controlador.
 *
 * Template para el controlador Usuarios
 */
class TplUser
{

    /**
     * Funcion que crea un template para la vista index del controlador
     * @param array $usuarios arreglo de los objetos usuarios
     * @return array $tpl devuelve el template de la vista index
     */
    function getIndex($usuarios)
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("admin/user/index.html");//ubicacion del html con el contenido
        $tpl = $this->generateCommonAssigns($tpl,$html);
        $tpl->assign("js_usuario", $html->includeJs('views/admin/user/index'));
        $tpl->assign("msg_error", Session::instance()->getAndClearFlash());
        

        foreach ($usuarios as $usuario)
        {
            $tpl->newBlock("listado");
            $tpl->assign(
                    array("id"=>$usuario['User']['id'],
                          "usuario"=>$usuario['User']['usuario'],
                          "nombre"=>$usuario['User']['nombre'],
                          "edit"=>$html->link($html->image('admin/16x16/new.png','Edit User'),'admin/user/add/'.$usuario['User']['id']),
                          "img_delete"=>$html->image('admin/16x16/delete.png','Delete User')
                         ));
        }

        $tpl->gotoBlock("_ROOT");

        return $tpl;
    }

    /**
     * Funcion que crea un template para la vista add del controlador
     * @param array $usuario arreglo del objeto usuario
     * @return array $tpl devuelve el template de la vista add
     */
    function getAdd($usuario)
    {
        $html = new HTML();
        $tpl= new IUGOTemplate("admin/user/add.html");//ubicacion del html con el contenido
        $tpl = $this->generateCommonAssigns($tpl,$html);
        $tpl->assign("js_usuario", $html->includeJs('views/admin/user/add'));
        $tpl->assign("msg_error", Session::instance()->getAndClearFlash());
        
        $tpl->assign("start_form",$html->startForm('form_user','form_user','/admin/user/add','','','form'));
        $tpl->assign("btn_save",'<button class="button" type="submit" onclick="$(\'#form_user\').submit();">'.$html->image('admin/icons/tick.png', 'Save').' Save </button>');
        $tpl->assign("close",$html->link('Cancel','admin/user/index/','','','text_button_padding link_button'));
        $tpl->assign("end_form",$html->endForm(''));

        $tpl->assign("id_user",$usuario['User']['id']);
        $tpl->assign("usuario_user",$usuario['User']['usuario']);
        $tpl->assign("nombre_user",$usuario['User']['nombre']);
        if(isset($usuario['User']['id'])){
            $tpl->assign("display_pass","style='display:none;'");
            $tpl->assign("mod_pass",'<a id="mensaje_pass" href="#" onclick="modificarPass();">Cambiar contrase&ntilde;a</a>');
        }

        return $tpl;
    }
    
    /**
     * Genera asignaciones que se van a usar en todos los templates,
     * como ser cosas del menú, íconos, etc.
     * @param Object $tpl un objeto del tipo IUGOTemplate
     * @param Object $html un objeto del tipo HTML
     * @return Object el mismo $tpl que recibió con mas cosas asignadas
     */
    private function generateCommonAssigns($tpl,$html)
    {
        $tpl->assign(array("icon_add"=>$html->image('admin/16x16/new.png','Add new'),
                           "new_user_btn"=>'<button class="button" type="submit" onclick="location.href=\''.BASE_PATH.'/admin/user/add/\'; return false;" >'.
                                            $html->image('admin/16x16/new.png','Add new').
                                            'Add new</button>',
                           "new_user_link"=>$html->link('Add new','admin/user/add/'),
                           "list_users_link"=>$html->link('List Users','admin/user/index/'),
                           "icon_delete"=>$html->image('admin/icons/cross.png','Delete')
                    ));
        return $tpl;
    }

}
?>
