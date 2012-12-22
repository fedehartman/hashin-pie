<?php

/****************************************
* Clase generada con IUGOGenerator v0.1
* Fecha: 19/12/2012
* Archivo: promotioncontroller.php
****************************************/

/*
* Controlador Promotions
*/

class PromotionController extends IugoController
{

	function beforeAction()
	{
		verificarLogin();
	}

	/**
	* Funcion para mostrar el listado de los promotions
	* @param int $pagina pagina en la que estoy ubicado
	*/
	function index($pagina)
	{
		try
		{
			$tplContainer = new TplContainer();
			$oContainer = $tplContainer->getContainer();

			if($pagina=="")$pagina = 1;
			$promotion  = new Promotion();
			$promotion->orderBy('id','DESC');
			$promotion->setPage($pagina);
			$promotion->setLimit(50);
			$promotions = $promotion->search();

			$tplPromotion = new TplPromotion();
			$oIndex = $tplPromotion->getIndex($promotions);

			if(count($promotions)>0)
			{
				$paginador = new Paginador(strtolower($this->_controller), $this->_action,$promotion,$pagina);
				$oIndex->assign("paginado",$paginador->mostrarPaginado());
			}

			$oContainer->assign('CONTENT', $oIndex->getOutputContent());
			$oContainer->printToScreen();

		}catch (Exception $ex)
		{
			print_r($ex->getMessage());
		}
	}

	/**
	* Funcion para agregar un nuevo promotion
	*/
	function add($id)
	{
		try
		{
			$tplContainer = new TplContainer();
			$oContainer = $tplContainer->getContainer();

			if (safePostVar('submit') != '')
			{
				$promotion = new Promotion();
				if(safePostVar('id')){$promotion->id = safePostVar('id');}
				$promotion->titulo = safePostVar('titulo');
				$promotion->descripcion = safePostVar('descripcion');

				$imagen = $_FILES['foto'];
				if ($imagen) 
				{
					$upload   = new IUGOFileUpload();
					$upload->allow('images');
					$upload->set_path(IMAGES_UPLOAD_DIR);
					$upload->set_max_size(MAX_UPLOAD_FILESIZE * 1000000);
					$filename = $upload->upload($imagen);
					if ($upload->is_error())
					{
						if ($upload->_errno != "4")
						{
							$response->error =true;
							$response->msg =$upload->get_error();
						}
					}else
					{
						$promotion->foto = $filename;

					}
				}

				$promotion->save();

				Session::instance()->setFlash(MSG_GUARDADO,'notice');
				redirectAction('/admin/promotion/index/',true);
			}else
			{
				if($id)
				{
					$promotion = new Promotion();
					$promotion->id = $id;
					$promotion = $promotion->search();
				}
			}

			$tplPromotion = new TplPromotion();
			$oAdd = $tplPromotion->getAdd($promotion);

			$oContainer->assign('CONTENT', $oAdd->getOutputContent());
			$oContainer->printToScreen();

		}catch (Exception $ex)
		{
			print_r($ex->getMessage());
		}
	}

	/**
	* Funcion para editar un promotion
	*/
	function edit($id='')
	{
		try
		{
			$tplContainer = new TplContainer();
			$oContainer = $tplContainer->getContainer();

			if ($id == '') $id = safePostVar('id');
			if (safePostVar('submit') != '')
			{
				$promotion = new Promotion();
				$promotion->id = $id;
				$promotion->titulo = safePostVar('titulo');
				$promotion->foto = safePostVar('foto');
				$promotion->descripcion = safePostVar('descripcion');
				$promotion->save();

				Session::instance()->setFlash(MSG_GUARDADO,'notice');
				redirectAction('/admin/promotion/index/',true);
			}
			else
			{
				if($id)
				{
					$promotion = new Promotion();
					$promotion->id = $id;
					$promotion = $promotion->search();
				}
			}

			$tplPromotion = new TplPromotion();
			$oEdit = $tplPromotion->getEdit($promotion);

			$oContainer->assign('CONTENT', $oEdit->getOutputContent());
			$oContainer->printToScreen();

		}catch (Exception $ex)
		{
			print_r($ex->getMessage());
		}
	}

	/**
	* Funcion para borrar un promotion
	*/
	function delete()
	{
		try
		{
			$data = safePostGetVar('id');
			$promotion = new Promotion();
			foreach ($data as $id)
			{
				$promotion->id = $id;
				$promotion->delete();
			}
			echo "ok";

		}catch (Exception $ex)
		{
			print_r($ex->getMessage());
		}
	}

}
