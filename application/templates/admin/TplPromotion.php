<?php

/****************************************
* Clase generada con IUGOGenerator v0.1
* Fecha: 19/12/2012
* Archivo: TplPromotion.php
****************************************/

/*
* Template Promotions
*/

class TplPromotion
{

	/**
	* Funcion que crea un template para la vista index del controlador
	* @param array $promotions arreglo de los objetos Promotions
	* @return array $tpl devuelve el template de la vista index
	*/
	function getIndex($promotions)
	{
		$html = new HTML();
		$tpl = new IUGOTemplate("admin/promotion/index.html");
		$tpl = $this->generateCommonAssigns($tpl,$html);
		$tpl->assign("js_promotion", $html->includeJs('views/admin/promotion/index'));
		$tpl->assign("msg_error", Session::instance()->getAndClearFlash());

		foreach ($promotions as $promotion)
		{
			$tpl->newBlock("listado");
			$tpl->assign(
				array(	"id"=>$promotion['Promotion']['id'],
					"titulo"=>$promotion['Promotion']['titulo'],
					"foto"=>$promotion['Promotion']['foto'],
					"edit"=>$html->link($html->image('admin/16x16/new.png','Edit'),'admin/promotion/edit/'.$promotion['Promotion']['id']),
					"img_delete"=>$html->image('admin/16x16/delete.png','Delete')
				));
		}
		$tpl->gotoBlock("_ROOT");

		return $tpl;
	}

	/**
	* Funcion que crea un template para la vista add del controlador
	* @param array $promotion arreglo con el Promotion
	* @return array $tpl devuelve el template de la vista add
	*/
	function getAdd($promotion)
	{
		$html = new HTML();
		$tpl = new IUGOTemplate("admin/promotion/add.html");
		$tpl = $this->generateCommonAssigns($tpl,$html);
		$tpl->assign("js_promotion", $html->includeJs('views/admin/promotion/add'));
		$tpl->assign("msg_error", Session::instance()->getAndClearFlash());

		$tpl->assign("start_form",$html->startForm('form_promotion','form_promotion','/admin/promotion/add', 'post', 'multipart/form-data','form'));
		$tpl->assign("btn_save",'<button class="button" type="submit">'.$html->image('admin/icons/tick.png', 'Save').' Save </button>');
		$tpl->assign("close",$html->link('Cancel','admin/promotion/index/','','','text_button_padding link_button'));
		$tpl->assign("end_form",$html->endForm(''));

		return $tpl;
	}

	/**
	* Funcion que crea un template para la vista edit del controlador
	* @param array $promotion arreglo con el Promotion
	* @return array $tpl devuelve el template de la vista add
	*/
	function getEdit($promotion)
	{
		$html = new HTML();
		$tpl = new IUGOTemplate("admin/promotion/edit.html");
		$tpl = $this->generateCommonAssigns($tpl,$html);
		$tpl->assign("js_promotion", $html->includeJs('views/admin/promotion/edit'));
		$tpl->assign("msg_error", Session::instance()->getAndClearFlash());

		$tpl->assign("start_form",$html->startForm('form_promotion','form_promotion','/admin/promotion/edit', 'post', 'multipart/form-data','form'));
		$tpl->assign("btn_save",'<button class="button" type="submit">'.$html->image('admin/icons/tick.png', 'Save').' Save </button>');
		$tpl->assign("close",$html->link('Cancel','admin/promotion/index/','','','text_button_padding link_button'));
		$tpl->assign("end_form",$html->endForm(''));

		$tpl->assign('id',$promotion['Promotion']['id']);
		$tpl->assign('titulo',$promotion['Promotion']['titulo']);
		$tpl->assign('foto',$promotion['Promotion']['foto']);
		$tpl->assign('descripcion',$promotion['Promotion']['descripcion']);
		return $tpl;
	}

	/**
	* Genera asignaciones que se van a usar en todos los templates,
	* como ser cosas del menu, iconos, etc.
	* @param Object $tpl un objeto del tipo IUGOTemplate
	* @param Object $html un objeto del tipo HTML
	* @return Object el mismo $tpl que recibio con mas cosas asignadas
	*/
	private function generateCommonAssigns($tpl,$html)
	{
		$tpl->assign(
			array(	'icon_add'=>$html->image('admin/16x16/new.png','Add new'),
				'new_btn'=>'<button class="button" type="submit" onclick="location.href=\''.BASE_PATH.'/admin/promotion/add/\'; return false;" >'.$html->image('admin/16x16/new.png','Add new').'Add new</button>',
				'new_link'=>$html->link('Add new','admin/promotion/add/'),
				'list_link'=>$html->link('List Promociones','admin/promotion/index/'),
				'icon_delete'=>$html->image('admin/icons/cross.png','Delete')
			));
		return $tpl;
	}

}