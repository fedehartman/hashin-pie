<?php
/*
 * Controlador main
 * 
 */

class PromocionesController extends IugoController {

    function beforeAction()
    {
    }


    function index()
    {
        try
        {
           // $tplContainer = new TplFrtContainer();
            //$oContainer = $tplContainer->getContainer();

            $tplMain = new TplPromociones();
            $oIndex = $tplMain->getIndex(Promotion::getAllPromos());

            //$oContainer->assign('CONTENT', $oIndex->getOutputContent());
            $oIndex->printToScreen();

        }catch (Exception $ex)
        {
            print_r($ex->getMessage());
        }
    }



    function afterAction() {
    }

}
