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

    function enviarContacto()
    {
        $nombre = safePostVar('nombre');
        $email = safePostVar('email');
        $tel = safePostVar('tel');
        $motivo = safePostVar('motivo');
        $mensaje = safePostVar('mensaje');
        $personas = safePostVar('personas');
        $entrada = safePostVar('entrada');
        $salida = safePostVar('salida');

        $subject = $motivo." desde la web";

        $cuerpo = "Nombre: ".$nombre." <br>";
        $cuerpo .= "Email: ".$email." <br>";
        $cuerpo .= "Telefono: ".$tel." <br>";
        $cuerpo .= "Motivo: ".$motivo." <br>";
        $cuerpo .= "Cantidad de personas: ".$personas." <br>";
        $cuerpo .= "Entrada: ".$entrada." <br>";
        $cuerpo .= "Salida: ".$salida." <br>";
        $cuerpo .= "Mensaje: <br>".$mensaje;

        $to =   "mdaguerre@gmail.com";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'BCC: andesbotta@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        if (mail($to, $subject, $cuerpo, $headers))
        {
            $return['error'] = false;
            $return['msg']     = "Mail enviado";
        }else
        {
            $return['error'] = true;
        }

        echo json_encode($return);
        
    }



    function afterAction() {
    }

}
