<?php
/**
 * Clase que contiene los template para cada vista del controlador.
 *
 * Template para el controlador Main
 */
class TplPromociones
{

    function getIndex($promociones)
    {
        $html = new HTML();
        $tpl = new IUGOTemplate("front/promociones/index.html");

        foreach ($promociones as $promo) {
          $tpl->newBlock('PROMO');
          $tpl->assign('titulo',$promo['Promotion']['titulo']);
          $tpl->assign('src',IMAGES_DIR.$promo['Promotion']['foto']);
          $tpl->assign('descripcion',$promo['Promotion']['descripcion']);
        }

        return $tpl;
    }

}
?>
