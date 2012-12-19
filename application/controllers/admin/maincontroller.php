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

    function afterAction() {
    }
	
}
