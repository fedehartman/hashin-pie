<?php
/*
 * Controlador main
 * 
 */

class MainController extends IugoController {
	
    function beforeAction()
    {
        verificarLogin();
    }

   /**
     * Función para mostrar el menu principal
     * @param int $pagina pagina en la que estoy ubicado
     */
   function index()
   {
    try
    {
        $tplContainer = new TplContainer();
        $oContainer = $tplContainer->getContainer();

        $tplMain = new TplMain();
        $oIndex = $tplMain->getIndex();

        $oContainer->assign('CONTENT', $oIndex->getOutputContent());
        $oContainer->printToScreen();

    }catch (Exception $ex)
    {
        print_r($ex->getMessage());
    }
}


function fondosHome()
{
 try
 {
    $tplContainer = new TplContainer();
    $oContainer = $tplContainer->getContainer();

    if (safePostVar('submit') != '')
    {
        //if(safePostVar('id')){$imagen->id = safePostVar('id');}


        if($_FILES)
        {
            $id_imagenes = $_POST['imagen_id'];

            foreach ($_FILES as $key => $imagen) 
            {
                //print_r($imagen);die(); //Debug

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
                    if($id_imagenes[$key])
                    {
                        $image->id = $id_imagenes[$key];
                    }
                    $image->home =1;
                    $image->path = $filename;
                    $image->save();
                }
            }
        }

        Session::instance()->setFlash(MSG_GUARDADO,'notice');
        redirectAction('/admin/main/fondosHome/',true);
    }else
    {
        $image = new Image();
        $image->where('home',1);
        $images = $image->search();

    }


    $tplMain = new TplMain();
    $oIndex = $tplMain->getFondosHome($images);

    $oContainer->assign('CONTENT', $oIndex->getOutputContent());
    $oContainer->printToScreen();

}catch (Exception $ex)
{
    print_r($ex->getMessage());
}
}

function afterAction() {
}

}
