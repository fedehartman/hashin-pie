<?php

/****************************************
* Clase generada con IUGOGenerator v0.1
* Fecha: 19/12/2012
* Archivo: image.php
****************************************/

/*
* Modelo Images
*/

class Image extends IugoModel
{
	function completeDelete()
	{
		
		$this->delete();
	}

 static function fondosHome()
 {
  $image = new Image();
  $image->where('home',1);
  return $image->search();
}
}
