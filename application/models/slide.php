<?php

class Slide extends IugoModel {

  static function obtenerImagenes($id)
  {
    $image = new Image();
    $imagenes = $image->custom("SELECT images.* FROM images,slide_images WHERE slide_images.slide_id=".$id." and slide_images.image_id = images.id ");
    return $imagenes;        
  }  
  
}
