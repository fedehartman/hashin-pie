<?php

class Gallery extends IugoModel {


  static function obtenerGaleria($tipo,$personas)
  {
    $galeria =  new Gallery();
    $galeria->where('tipo',$tipo);
    $galeria->where('personas',$personas);
    $galeria = $galeria->search();
  
    if($galeria[0]['Gallery']['id'])
    {
      $imagenes = Image::obtenerImagenesGaleria($galeria[0]['Gallery']['id']);
      $galeria[0]['Imagenes'] = $imagenes;
    }

    return $galeria[0];
  }

   static function obtenerGaleriaById($id)
  {
    $galeria =  new Gallery();
    $galeria->where('id',$id);
    $galeria = $galeria->search();
  
    if($galeria[0]['Gallery']['id'])
    {
      $imagenes = Image::obtenerImagenesGaleria($galeria[0]['Gallery']['id']);
      $galeria[0]['Imagenes'] = $imagenes;
    }

    return $galeria[0];
  }
  
}
