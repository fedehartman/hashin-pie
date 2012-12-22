<?php
/*
 * Controlador main
 * 
 */

class HomeController extends IugoController {

    function beforeAction()
    {
    }


    function index()
    {
        try
        {
            $tplContainer = new TplFrtContainer();
            $oContainer = $tplContainer->getContainer();

          // $tplMain = new TplHome();
           // $oIndex = $tplMain->getHome(Image::fondosHome());

            //$oContainer->assign('CONTENT', $oIndex->getOutputContent());
            $oContainer->printToScreen();

        }catch (Exception $ex)
        {
            print_r($ex->getMessage());
        }
    }



    function afterAction() {
    }

}
