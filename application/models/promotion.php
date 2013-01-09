<?php

/****************************************
* Clase generada con IUGOGenerator v0.1
* Fecha: 19/12/2012
* Archivo: promotion.php
****************************************/

/*
* Modelo Promotions
*/

class Promotion extends IugoModel
{

  static function getAllPromos()
  {
    $promotion = new Promotion();
    $promotions = $promotion->search();
    return $promotions;
  }

  function completeDelete()
  {
    
    $this->delete();
  }
}
