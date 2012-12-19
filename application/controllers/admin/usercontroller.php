<?php
/*
 * Controlador Usuarios
 * 
 */

class UserController extends IugoController {
	
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
            $usuario  = new User();
            $usuario->orderBy('id','DESC');//seteo la pagina para el paginado
            $usuario->setPage($pagina);//seteo la pagina para el paginado
            $usuario->setLimit(15);//seteo el limite para el paginado
            $usuarios = $usuario->search();

            $tplUser = new TplUser();
            $oIndex = $tplUser->getIndex($usuarios);//$oIndex variable que contiene la plantilla index

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
            if ($id == '') $id = safePostVar('id');

            $tplContainer = new TplContainer();
            if ($id == logged_user()->id)
            {
                $oContainer = $tplContainer->getContainer('profile');
            }
            else
            {
                $oContainer = $tplContainer->getContainer('home');
            }

            //Si vengo de un formulario, guardo los datos.
            if(safePostVar('submit')!='')
            {
                if(safePostVar('clave'))
                {
                    $clave = getPasswordHash( getPasswordSalt(), safePostVar('clave'));
                } 
                $user = new User();
                if($id != "")
                {
                   $user->id=$id; 
                }                
                $user->usuario = safePostVar('usuario');
                $user->clave = $clave;
                $user->nombre = safePostVar('nombre');                
                
                try {
                    $user->save();
                } catch (Exception $e) {
                    Session::instance()->setFlash($e->getMessage(),'error');
                    redirectAction('/admin/user/index/',true);
                }
                Session::instance()->setFlash(MSG_GUARDADO,'notice');
                redirectAction('/admin/user/index/',true);
            }
            else
            {
                if($id)
                {
                    $user = new User();
                    $user->id=$id;
                    $usuario=$user->search();
                    $titulo = 'Edit user "'.$usuario["User"]['nombre'].'"';
                }
                else
                {
                    $titulo = 'Add user';
                }  
            }

            $tplUser = new TplUser();
            $oAdd = $tplUser->getAdd($usuario);//$oAdd variable que contiene la plantilla add
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
