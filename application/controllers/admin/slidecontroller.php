<?php
/*
 * Controlador Usuarios
 * 
 */

class SlideController extends IugoController {

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
            $slide  = new Slide();
            $slide->orderBy('id','DESC');//seteo la pagina para el paginado
            $slide->setPage($pagina);//seteo la pagina para el paginado
            $slide->setLimit(15);//seteo el limite para el paginado
            $slides = $slide->search();

            $tplSlide = new TplSlide();
            $oIndex = $tplSlide->getIndex($slides);//$oIndex variable que contiene la plantilla index

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
        try
        {
                
            $tplContainer = new TplContainer();

            $oContainer = $tplContainer->getContainer();
            if ($id == '') $id = safePostVar('id');

            if (safePostVar('submit'))
            {



                $slide = new Slide();
                if($id){$slide->id=safePostVar('id');}
                $slide->nombre = safePostVar('nombre');  
                $slide->save();              

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

                            $slide_image = new Slide_image();
                            $slide_image->slide_id = $slide->id;
                            $slide_image->image_id = $image->id;
                            $slide_image->save();
                        }
                    }


                }


                    Session::instance()->setFlash(MSG_GUARDADO,'notice');
                    redirectAction('/admin/slide/index/',true);
            }
            else
            {
                if($id)
                {

                    $slide = new Slide();
                    $slide->id=$id;
                    $slide=$slide->search();
                    $slide['Images'] = Slide::obtenerImagenes($id);

                }

            }

            $tplSlide = new TplSlide();
            $oAdd = $tplSlide->getAdd($slide);//$oAdd variable que contiene la plantilla add
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
