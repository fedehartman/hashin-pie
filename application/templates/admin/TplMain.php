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

    function getFondosHome($fondos = '')
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("admin/home/fondosHome.html");

        $tpl->assign("start_form",$html->startForm('form_promotion','form_promotion','/admin/main/fondosHome', 'post', 'multipart/form-data','form'));
        $tpl->assign("btn_save",'<button class="button" type="submit">'.$html->image('admin/icons/tick.png', 'Save').' Save </button>');
        $tpl->assign("close",$html->link('Cancel','admin/main/fondosHome/','','','text_button_padding link_button'));
        $tpl->assign("end_form",$html->endForm(''));

        $c =1;
        foreach ($fondos as $imagen) 
        {
            $tpl->newBlock('IMAGEN_'.$c);
            $tpl->assign('src',IMAGES_DIR.$imagen['Image']['path']);
            $tpl->assign('id',$imagen['Image']['id']);
            $c++;
        }

        return $tpl;
    }
}
?>
