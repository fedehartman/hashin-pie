<?php

class Module 
{    
    /**
     * Crea el modulo con todos sus archivos
     * @param array $tabla 
     */
    public function create($tabla)
    {
        include("includes/config.php");               
        include("clases/Writer.php");
        include("clases/Db.php");
        include("clases/Table.php");
        include("clases/Model.php");
        include("clases/Controller.php");
        include("clases/View.php");
        include("clases/Template.php");
        include("clases/Js.php");      
        
        if($tabla['action']['table'] == 1)
        {
            $this->crearTable($tabla);
        }
        if($tabla['action']['model'] == 1)
        {
            $this->crearModel($tabla);
        }
        if($tabla['action']['controller'] == 1)
        {
            $this->crearController($tabla);
        }
        if($tabla['action']['view'] == 1)
        {
            $this->crearView($tabla);
        }
        if($tabla['action']['template'] == 1)
        {
            $this->crearTemplate($tabla);
        }
        if($tabla['action']['js'] == 1)
        {
            $this->crearJs($tabla);
        }
    }
    
    /**
     *  Crea Table
     * 
     * @param string $tabla
     */
    private function crearTable($tabla)
    {
        $model = new Table();
        $model->create($tabla);
    }
    
    /**
     *  Crea Model
     * 
     * @param string $tabla
     */
    private function crearModel($tabla)
    {
        $model = new Model();
        $model->create($tabla);
    }
    
    /**
     *  Crea Contoller
     * 
     * @param string $tabla
     */
    private function crearController($tabla)
    {
        $controller = new Controller();
        $controller->create($tabla);
    }
    
    /**
     *  Crea View
     * 
     * @param string $tabla
     */
    private function crearView($tabla)
    {
        $view = new View();
        $view->create($tabla);
    }
    
    /**
     *  Crea Template
     * 
     * @param string $tabla
     */
    private function crearTemplate($tabla)
    {
        $template = new Template();
        $template->create($tabla);
    }
    
    /**
     *  Crea JS
     * 
     * @param string $tabla
     */
    private function crearJs($tabla)
    {
        $js = new Js();
        $js->create($tabla);
    }
}