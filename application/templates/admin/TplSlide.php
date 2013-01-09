<?php
/**
 * Clase que contiene los template para cada vista del controlador.
 *
 * Template para el controlador Main
 */
class TplSlide
{

    /**
     * Funcion que crea un template para la vista index del controlador
     * @return array $tpl devuelve el template de la vista index
     */
    function getIndex($slides)
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("admin/slide/index.html");
        $tpl->assign("msg_error", Session::instance()->getAndClearFlash());   

        foreach ($slides as $slide)
        {
            $tpl->newBlock("listado");
            $tpl->assign(
                array("id"=>$slide['Slide']['id'],
                  "nombre"=>$slide['Slide']['nombre'],
                  "edit"=>$html->link($html->image('admin/16x16/new.png','Edit User'),'admin/slide/add/'.$slide['Slide']['id']),
                  "img_delete"=>$html->image('admin/16x16/delete.png','Delete User')
                  ));
        }

        return $tpl;
    }

    function getAdd($slide = '')
    {
            
        $html = new HTML();
        $tpl = new IUGOTemplate("admin/slide/add.html");

        $tpl->assign("start_form",$html->startForm('form_promotion','form_promotion','/admin/slide/add', 'post', 'multipart/form-data','form'));
        $tpl->assign("btn_save",'<button class="button" type="submit">'.$html->image('admin/icons/tick.png', 'Save').' Save </button>');
        $tpl->assign("close",$html->link('Cancel','admin/slide/index/','','','text_button_padding link_button'));
        $tpl->assign("end_form",$html->endForm(''));

        $tpl->assign('id',$slide['Slide']['id']);
        $tpl->assign('nombre',$slide['Slide']['nombre']);

        $c =1;
        foreach ($slide['Images'] as $imagen) 
        {
            $tpl->newBlock('FOTO');
            $tpl->assign('src',IMAGES_DIR.$imagen['Image']['path']);
            $tpl->assign('id',$imagen['Image']['id']);
            $c++;
        }

        return $tpl;
    }
}
?>
