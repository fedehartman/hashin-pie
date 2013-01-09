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

    $fondos = Image::fondosHome();
    $tpl->assign('fondo_1',IMAGES_DIR.$fondos[0]['Image']['path']);
    $tpl->assign('fondo_2',IMAGES_DIR.$fondos[1]['Image']['path']);
    $tpl->assign('fondo_3',IMAGES_DIR.$fondos[2]['Image']['path']);

    $slide =  Slide::obtenerImagenes(1);

    foreach ($slide as $imagen) {
     $tpl->newBlock('IMAGEN_SLIDE');
     $tpl->assign('path',IMAGES_DIR.$imagen['Image']['path']);
   }

   $tpl->gotoBlock('_ROOT');

   $tipos = array('Apartamento','Bungalows');
   $personas = array(2,3,4,5,6);

   foreach($tipos as $tipo)
   {

    foreach($personas as $cantidadPersonas)
    {
      $galeria = Gallery::obtenerGaleria($tipo,$cantidadPersonas);
      $tpl->newBlock(strtoupper($tipo).'_PERSONAS');
      $tpl->assign('cantidad',$cantidadPersonas);
      if($galeria)
      {
        $tpl->newBlock('GALERIA_'.strtoupper($tipo));
        $tpl->assign('clase',strtolower($tipo).'_'.$cantidadPersonas);
        foreach ($galeria['Imagenes'] as $imagen) {
          $tpl->newBlock('IMAGEN_APARTAMENTO');
          $tpl->assign('src',IMAGES_DIR.$imagen['Image']['path']);

        }

      }

    }


  }

  return $tpl;
}

}
?>
