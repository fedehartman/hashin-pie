<?php
/*
 * Controlador Usuarios
 * 
 */

class GalleryController extends IugoController {

	function beforeAction()
	{
		verificarLogin();
	}

   /**
	 * Función para mostrar el listado de los "Users"
	 * @param int $pagina pagina en la que estoy ubicado
	 */
   function index($pagina)
   {
	try
	{
		$tplContainer = new TplContainer();
			$oContainer = $tplContainer->getContainer();//cargo la variables del container
			if($pagina=="")$pagina = 1;
			$galleria  = new Gallery();
			$galleria->orderBy('id','ASC');//seteo la pagina para el paginado
			$galleria->setPage($pagina);//seteo la pagina para el paginado
			$galleria->setLimit(15);//seteo el limite para el paginado
			$gallerias = $galleria->search();

			$tplUser = new TplGaleria();
			$oIndex = $tplUser->getIndex($gallerias);//$oIndex variable que contiene la plantilla index

			if(count($usuarios)>0)
			{
				//paginado
				$paginador = new Paginador(strtolower($this->_controller), $this->_action,$usuario,$pagina);
				$oIndex->assign("paginado",$paginador->mostrarPaginado());
			//
			}

			$oContainer->assign('CONTENT', $oIndex->getOutputContent());//al centenedor le agrego en la variable CONTENT el contenido
			$oContainer->printToScreen();

		}catch (Exception $ex)
		{
			print_r($ex->getMessage());
		}
	}



	/**
	 * Funcion par agregar o editar un "User"
	 * @param int $id si estoy editando un "user" le paso el id del mismo, sino se pasa vacio porque es un alta
	 */
	function add($id='')
	{
		$tplContainer = new TplContainer();
			$oContainer = $tplContainer->getContainer();//cargo la variables del container
			try
			{
				if ($id == '') $id = safePostVar('id');

				$tplContainer = new TplContainer();

			//Si vengo de un formulario, guardo los datos.
				if(safePostVar('submit')!='')
				{

					$gallery = new Gallery();
					if($id != "")
					{
					 $gallery->id=$id; 
				 }      

				 $no_borrar=safePostVar('no_borrar'); 
				         
				 if($_FILES)
				 {
					foreach ($_FILES as $imagen) 
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
							$image =  new Image();
							$image->path = $filename;
							$image->home = 0;
							$image->save();

							$gallery_image = new Gallery_image();
							$gallery_image->gallery_id = $id;
							$gallery_image->image_id = $image->id;
							$gallery_image->save();

							$no_borrar[] = $image->id;
						}
					}


				}

				   // print_r($no_borrar);die(); //Debug
						

				if($no_borrar)
				{
					$ids = implode(',', $no_borrar);
					$image= new Image();
					$image->custom("DELETE from gallery_images WHERE gallery_id=".safePostVar('id')." and image_id not in(".$ids.")");
				}

				try {
				   // $user->save();
				} catch (Exception $e) {
					Session::instance()->setFlash($e->getMessage(),'error');
					redirectAction('/admin/gallery/index/',true);
				}
				Session::instance()->setFlash(MSG_GUARDADO,'notice');
				redirectAction('/admin/gallery/index/',true);
			}
			else
			{
				if($id)
				{
					/*$galeria = new Gallery();
					$galeria->id=$id;
					$galeria=$galeria->search();*/
					$galeria = Gallery::obtenerGaleriaById($id);
				}
				
			}

			$tpGaleria = new TplGaleria();
			$oAdd = $tpGaleria->getAdd($galeria);//$oAdd variable que contiene la plantilla add
			$oAdd->assign('titulo_accion', $titulo);//titulo de la accion en la vista

			$oContainer->assign('CONTENT', $oAdd->getOutputContent());//al centenedor le agrego en la variable CONTENT el contenido
			$oContainer->printToScreen();
		}catch (Exception $ex)
		{
			print_r($ex->getMessage());
		}
	}

	/**
	 * Funcion para borrar usuarios. Devuelve un "ok" si lo borra correctamente.
	 */
	function deleteSelected()
	{
		try
		{
			$data = safeGetVar('id');
			$usuario = new User();
			foreach ($data as $id)
			{
				$usuario->id = $id;
				$usuario->delete();
			}
			echo 'ok';
		}catch (Exception $ex)
		{
		 echo $ex->getMessage();
	 }
 }    

 function afterAction() {
 }

}
