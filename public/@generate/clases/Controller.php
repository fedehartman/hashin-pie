<?php

class Controller extends Writer
{    
    /**
     * Crea el controlador con todas sus funciones [beforeAction,index,add,edit,delete]
     * @param array $tabla 
     */
    public function create($tabla)
    {        
        $limit = 50;
        $module = $tabla["table"]["name_table"];
        $module_plural = $tabla["table"]["name_table_plural"];
        $campos = $tabla["columns"];

        $file_path = PATH_CONTROLLER.$module . "controller.php";
        $file = fopen($file_path, 'w') or die("can't open file");

        $this->definirClase($module, $module_plural, $file);
        $this->crearBeforeAction($file);
        $this->crearIndex($module, $module_plural, $campos, $limit, $file);
        $this->crearAdd($module, $campos, $file);
        $this->crearEdit($module, $campos, $file);
        $this->crearDelete($module, $campos, $file);
        foreach ($campos as $campo)
        {
            $this->crearFuncionesExtra($module, $campo, $file);
        }        
        $this->cerrarClase($file);

        fclose($file);
        chmod($file_path, 0777);
    }

    /**
     *  Crea el inicio del documento y define la clase del controlador
     * 
     * @param string $module
     * @param string $module_plural
     * @param file $file 
     */
    private function definirClase($module, $module_plural, $file)
    {
        $this->write("<?php\n\n", $file);
        $this->write("/****************************************\n", $file);
        $this->write("* Clase generada con IUGOGenerator v0.1\n", $file);
        $this->write("* Fecha: " . date('d/m/Y') . "\n", $file);
        $this->write("* Archivo: " . $module . "controller.php\n", $file);
        $this->write("****************************************/\n\n", $file);
        $this->write("/*\n", $file);
        $this->write("* Controlador " . ucfirst($module_plural) . "\n", $file);
        $this->write("*/\n\n", $file);

        //*****************
        // Defino la clase
        //*****************
        $this->write("class " . ucfirst($module) . "Controller extends IugoController\n", $file); // Defino la clase del Controller
        $this->write("{\n\n", $file);
    }

    /**
     * Crea la funcion before action
     * 
     * @param file $file 
     */
    private function crearBeforeAction($file)
    {
        //************************
        // Funcion beforeAction()
        //************************
        $this->write("\tfunction beforeAction()\n", $file);
        $this->write("\t{\n", $file);
        $this->write("\t\tverificarLogin();\n", $file);
        $this->write("\t}\n\n", $file);
    }

    /**
     *  Crea la funcion index
     * 
     * @param string $module
     * @param string $module_plural
     * @param int $limit
     * @param file $file 
     */
    private function crearIndex($module, $module_plural, $campos, $limit, $file)
    {
        $ordenar_nombre = "";
        foreach ($campos as $campo)
        {
            if($campo["type"] == "10")
            {
                $ordenar_nombre = $campo["name_db"];
            }
        }
        //*****************
        // Funcion index()
        //*****************
        $this->write("\t/**\n", $file);
        $this->write("\t* Funcion para mostrar el listado de los " . $module_plural . "\n", $file);
        $this->write("\t* @param int \$pagina pagina en la que estoy ubicado\n", $file);
        $this->write("\t*/\n", $file);
        $this->write("\tfunction index(\$pagina)\n", $file); // Funcion index
        $this->write("\t{\n", $file);
        $this->write("\t\ttry\n", $file);
        $this->write("\t\t{\n", $file);
        $this->write("\t\t\t\$tplContainer = new TplContainer();\n", $file);
        $this->write("\t\t\t\$oContainer = \$tplContainer->getContainer();\n\n", $file);

        $this->write("\t\t\tif(\$pagina==\"\")\$pagina = 1;\n", $file);
        $this->write("\t\t\t\$" . $module . "  = new " . ucfirst($module) . "();\n", $file);
        if($ordenar_nombre != "")
        {
            $this->write("\t\t\t\$" . $module . "->orderBy('" . $ordenar_nombre . "','ASC');\n", $file);
        }
        else
        {
            $this->write("\t\t\t\$" . $module . "->orderBy('id','DESC');\n", $file);
        }        
        $this->write("\t\t\t\$" . $module . "->setPage(\$pagina);\n", $file);
        $this->write("\t\t\t\$" . $module . "->setLimit(" . $limit . ");\n", $file);
        $this->write("\t\t\t\$" . $module_plural . " = \$" . $module . "->search();\n\n", $file);
        $this->write("\t\t\t\$tpl" . ucfirst($module) . " = new Tpl" . ucfirst($module) . "();\n", $file);
        $this->write("\t\t\t\$oIndex = \$tpl" . ucfirst($module) . "->getIndex(\$" . $module_plural . ");\n\n", $file);

        $this->write("\t\t\tif(count(\$" . $module_plural . ")>0)\n", $file);
        $this->write("\t\t\t{\n", $file);
        $this->write("\t\t\t\t\$paginador = new Paginador(strtolower(\$this->_controller), \$this->_action,\$" . $module . ",\$pagina);\n", $file);
        $this->write("\t\t\t\t\$oIndex->assign(\"paginado\",\$paginador->mostrarPaginado());\n", $file);
        $this->write("\t\t\t}\n\n", $file);

        $this->write("\t\t\t\$oContainer->assign('CONTENT', \$oIndex->getOutputContent());\n", $file);
        $this->write("\t\t\t\$oContainer->printToScreen();\n\n", $file);

        $this->write("\t\t}catch (Exception \$ex)\n", $file);
        $this->write("\t\t{\n", $file);
        $this->write("\t\t\tprint_r(\$ex->getMessage());\n", $file);
        $this->write("\t\t}\n", $file);

        $this->write("\t}\n\n", $file);
    }

    /**
     *  Crea la funcion add
     * 
     * @param string $module
     * @param array $campos 
     * @param file $file 
     */
    private function crearAdd($module, $campos, $file)
    {
        //*****************
        // Funcion add()
        //*****************
        $this->write("\t/**\n", $file);
        $this->write("\t* Funcion para agregar un nuevo " . $module . "\n", $file);
        $this->write("\t*/\n", $file);
        $this->write("\tfunction add()\n", $file); // Funcion add
        $this->write("\t{\n", $file);
        $this->write("\t\ttry\n", $file);
        $this->write("\t\t{\n", $file);
        $this->write("\t\t\t\$tplContainer = new TplContainer();\n", $file);
        $this->write("\t\t\t\$oContainer = \$tplContainer->getContainer();\n\n", $file);

        $this->write("\t\t\tif (safePostVar('submit') != '')\n", $file);
        $this->write("\t\t\t{\n", $file);
        $this->write("\t\t\t\t\$" . $module . " = new " . ucfirst($module) . "();\n", $file);
        foreach ($campos as $campo)
        {
            $this->escribirCampo($module, $campo, $file);
        }
        $this->write("\t\t\t\t\$" . $module . "->save();\n\n", $file);
        
        $this->write("\t\t\t\tSession::instance()->setFlash(MSG_GUARDADO,'notice');\n", $file);
        $this->write("\t\t\t\tredirectAction('/admin/" . $module . "/index/',true);\n", $file);
        $this->write("\t\t\t}\n\n", $file);

        $this->write("\t\t\t\$tpl" . ucfirst($module) . " = new Tpl" . ucfirst($module) . "();\n", $file);
        $this->write("\t\t\t\$oAdd = \$tpl" . ucfirst($module) . "->getAdd();\n\n", $file);

        $this->write("\t\t\t\$oContainer->assign('CONTENT', \$oAdd->getOutputContent());\n", $file);
        $this->write("\t\t\t\$oContainer->printToScreen();\n\n", $file);

        $this->write("\t\t}catch (Exception \$ex)\n", $file);
        $this->write("\t\t{\n", $file);
        $this->write("\t\t\tprint_r(\$ex->getMessage());\n", $file);
        $this->write("\t\t}\n", $file);

        $this->write("\t}\n\n", $file);
    }

    /**
     *  Crea la funcion edit
     * 
     * @param string $module
     * @param array $campos 
     * @param file $file 
     */
    private function crearEdit($module, $campos, $file)
    {
        //*****************
        // Funcion edit()
        //*****************
        $this->write("\t/**\n", $file);
        $this->write("\t* Funcion para editar un " . $module . "\n", $file);
        $this->write("\t*/\n", $file);
        $this->write("\tfunction edit(\$id='')\n", $file); // Funcion edit
        $this->write("\t{\n", $file);
        $this->write("\t\ttry\n", $file);
        $this->write("\t\t{\n", $file);
        $this->write("\t\t\t\$tplContainer = new TplContainer();\n", $file);
        $this->write("\t\t\t\$oContainer = \$tplContainer->getContainer();\n\n", $file);
        
        $this->write("\t\t\tif (\$id == '') \$id = safePostVar('id');\n", $file);   
        $this->write("\t\t\tif (safePostVar('submit') != '')\n", $file);
        $this->write("\t\t\t{\n", $file);
        $this->write("\t\t\t\t\$" . $module . " = new " . ucfirst($module) . "();\n", $file);        
        $this->write("\t\t\t\t\$" . $module . "->id = \$id;\n", $file);
        foreach ($campos as $campo)
        {
            $this->escribirCampo($module, $campo, $file);
        }
        $this->write("\t\t\t\t\$" . $module . "->save();\n\n", $file);

        $this->write("\t\t\t\tSession::instance()->setFlash(MSG_GUARDADO,'notice');\n", $file);
        $this->write("\t\t\t\tredirectAction('/admin/" . $module . "/index/',true);\n", $file);

        $this->write("\t\t\t}\n", $file);
        $this->write("\t\t\telse\n", $file);
        $this->write("\t\t\t{\n", $file);

        $this->write("\t\t\t\tif(\$id)\n", $file);
        $this->write("\t\t\t\t{\n", $file);
        $this->write("\t\t\t\t\t\$" . $module . " = new " . ucfirst($module) . "();\n", $file);
        $this->write("\t\t\t\t\t\$" . $module . "->id = \$id;\n", $file);
        $this->write("\t\t\t\t\t\$" . $module . " = \$" . $module . "->search();\n", $file);
        $this->write("\t\t\t\t}\n", $file);
        $this->write("\t\t\t}\n\n", $file);

        $this->write("\t\t\t\$tpl" . ucfirst($module) . " = new Tpl" . ucfirst($module) . "();\n", $file);
        $this->write("\t\t\t\$oEdit = \$tpl" . ucfirst($module) . "->getEdit(\$" . $module . ");\n\n", $file);

        $this->write("\t\t\t\$oContainer->assign('CONTENT', \$oEdit->getOutputContent());\n", $file);
        $this->write("\t\t\t\$oContainer->printToScreen();\n\n", $file);

        $this->write("\t\t}catch (Exception \$ex)\n", $file);
        $this->write("\t\t{\n", $file);
        $this->write("\t\t\tprint_r(\$ex->getMessage());\n", $file);
        $this->write("\t\t}\n", $file);

        $this->write("\t}\n\n", $file);
    }

    /**
     *  Crea la funcion delete
     * 
     * @param string $module
     * @param file $file 
     */
    private function crearDelete($module, $campos, $file)
    {
        $borrado_especial = false;
        foreach ($campos as $campo)
        {
            if($campo["type"] == "6" || $campo["type"] == "7")
            {
                $borrado_especial = true;
            }
        }        
        if($borrado_especial)
        {
            //*****************
            // Funcion delete()
            //*****************
            $this->write("\t/**\n", $file);
            $this->write("\t* Funcion para borrar un " . $module . "\n", $file);
            $this->write("\t*/\n", $file);
            $this->write("\tfunction delete()\n", $file); // Funcion delete
            $this->write("\t{\n", $file);
            $this->write("\t\ttry\n", $file);
            $this->write("\t\t{\n", $file);
            $this->write("\t\t\t\$data = safePostGetVar('id');\n", $file);
            $this->write("\t\t\t\$" . $module . " = new " . ucfirst($module) . "();\n", $file);
            $this->write("\t\t\tforeach (\$data as \$id)\n", $file);
            $this->write("\t\t\t{\n", $file);
            $this->write("\t\t\t\t\$" . $module . " = " . ucfirst($module) . "::getById(\$id);\n", $file);
            $this->write("\t\t\t\t\$" . $module . "->completeDelete();\n", $file);
            $this->write("\t\t\t}\n", $file);

            $this->write("\t\t\techo \"ok\";\n\n", $file);

            $this->write("\t\t}catch (Exception \$ex)\n", $file);
            $this->write("\t\t{\n", $file);
            $this->write("\t\t\tprint_r(\$ex->getMessage());\n", $file);
            $this->write("\t\t}\n", $file);

            $this->write("\t}\n\n", $file);
        }
        else
        {
            //*****************
            // Funcion delete()
            //*****************
            $this->write("\t/**\n", $file);
            $this->write("\t* Funcion para borrar un " . $module . "\n", $file);
            $this->write("\t*/\n", $file);
            $this->write("\tfunction delete()\n", $file); // Funcion delete
            $this->write("\t{\n", $file);
            $this->write("\t\ttry\n", $file);
            $this->write("\t\t{\n", $file);
            $this->write("\t\t\t\$data = safePostGetVar('id');\n", $file);
            $this->write("\t\t\t\$" . $module . " = new " . ucfirst($module) . "();\n", $file);
            $this->write("\t\t\tforeach (\$data as \$id)\n", $file);
            $this->write("\t\t\t{\n", $file);
            $this->write("\t\t\t\t\$" . $module . "->id = \$id;\n", $file);
            $this->write("\t\t\t\t\$" . $module . "->delete();\n", $file);
            $this->write("\t\t\t}\n", $file);

            $this->write("\t\t\techo \"ok\";\n\n", $file);

            $this->write("\t\t}catch (Exception \$ex)\n", $file);
            $this->write("\t\t{\n", $file);
            $this->write("\t\t\tprint_r(\$ex->getMessage());\n", $file);
            $this->write("\t\t}\n", $file);

            $this->write("\t}\n\n", $file);
        }        
    }
    
    /**
     * Crea las funciones del controlador para los campos dependiendo del tipo
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function crearFuncionesExtra($module, $campo, $file)
    {
        switch ($campo["type"])
        {
            case '6': //imagen
                //*****************
                // Funcion crop()
                //*****************
                $this->write("\t/**\n", $file);
                $this->write("\t* Crop de foto para gallery\n", $file);
                $this->write("\t*/\n", $file);
                $this->write("\tfunction crop(\$id)\n", $file);
                $this->write("\t{\n", $file);
                $this->write("\t\ttry\n", $file);
                $this->write("\t\t{\n", $file);
                $this->write("\t\t\t\$tplContainer = new TplPopupContainer();\n", $file);
                $this->write("\t\t\t\$oContainer = \$tplContainer->getContainer();\n\n", $file);
                $this->write("\t\t\t\$" . $module . " = " . ucfirst($module) . "::getById(\$id);\n\n", $file);
                $this->write("\t\t\t\$tpl" . ucfirst($module) . " = new Tpl" . ucfirst($module) . "();\n", $file);
                $this->write("\t\t\t\$oCrop = \$tpl" . ucfirst($module) . "->getCrop(\$" . $module . ");\n\n", $file);
                $this->write("\t\t\t\$oContainer->assign('CONTENT', \$oCrop->getOutputContent());\n", $file);
                $this->write("\t\t\t\$oContainer->printToScreen();\n\n", $file);
                $this->write("\t\t}catch (Exception \$ex)\n", $file);
                $this->write("\t\t{\n", $file);
                $this->write("\t\t\tprint_r(\$ex->getMessage());\n", $file);
                $this->write("\t\t}\n", $file);
                $this->write("\t}\n\n", $file);
                
                //*****************
                // Guarda el thumb
                //*****************
                $this->write("\t/**\n", $file);
                $this->write("\t* Guarda el thumb\n", $file);
                $this->write("\t*/\n", $file);
                $this->write("\tfunction saveThumbnails(\$id)\n", $file);
                $this->write("\t{\n", $file);
                $this->write("\t\ttry\n", $file);
                $this->write("\t\t{\n", $file);
                $this->write("\t\t\t\$path = " . ucfirst($module) . "::img_path;\n", $file);
                $this->write("\t\t\t\$width = " . ucfirst($module) . "::img_width;\n\n", $file);
                $this->write("\t\t\t\$pic = " . ucfirst($module) . "::getById(\$id);\n", $file);
                $this->write("\t\t\t\$oImage = new IUGOImage(" . ucfirst($module) . "::original_path);\n", $file);
                $this->write("\t\t\t\$oImage->setOriginal_name(\$pic->" . $campo["name_db"] . ");\n", $file);
                $this->write("\t\t\t\$upl = \$oImage->createThumbnail(\$path,safePostVar('x1'), safePostVar('x2'), safePostVar('y1'), safePostVar('y2'), safePostVar('w'), safePostVar('h'),\$width);\n", $file);
                $this->write("\t\t\tSession::instance()->setFlash(MSG_GUARDADO,'notice');\n\n", $file);
                $this->write("\t\t\t\$tplContainer = new TplPopupContainer();\n", $file);
                $this->write("\t\t\t\$oContainer = \$tplContainer->getClosePopup();\n", $file);
                $this->write("\t\t\t\$oContainer->printToScreen();\n\n", $file);
                $this->write("\t\t}catch (Exception \$ex)\n", $file);
                $this->write("\t\t{\n", $file);
                $this->write("\t\t\tprint_r(\$ex->getMessage());\n", $file);
                $this->write("\t\t}\n", $file);
                $this->write("\t}\n\n", $file);
                
                //*****************
                // Funcion para borrar las imagenes viejas
                //*****************
                $this->write("\t/**\n", $file);
                $this->write("\t* Funcion para borrar las imagenes viejas\n", $file);
                $this->write("\t*/\n", $file);
                $this->write("\tfunction borrar_imagen_viejas(\$id)\n", $file);
                $this->write("\t{\n", $file);
                $this->write("\t\ttry\n", $file);
                $this->write("\t\t{\n", $file);
                $this->write("\t\t\t\$" . $module . " = new " . ucfirst($module) . "();\n", $file);
                $this->write("\t\t\t\$" . $module . "->id = \$id;\n", $file);
                $this->write("\t\t\t\$result = \$" . $module . "->search();\n", $file);
                $this->write("\t\t\t\$" . $module . "->loadObject(\$result['" . ucfirst($module) . "']);\n\n", $file);
                $this->write("\t\t\tunlink(" . strtoupper($module) . "_PIC_ORIGINAL.'/'.\$" . $module . "->" . $campo["name_db"] . ");\n", $file);
                $this->write("\t\t\tunlink(" . strtoupper($module) . "_PIC_IMG.'/'.\$" . $module . "->" . $campo["name_db"] . ");\n\n", $file);
                $this->write("\t\t}catch (Exception \$ex)\n", $file);
                $this->write("\t\t{\n", $file);
                $this->write("\t\t\tprint_r(\$ex->getMessage());\n", $file);
                $this->write("\t\t}\n", $file);
                $this->write("\t}\n\n", $file);
                break;
            case '10': //orden
                $this->write("\t/**\n", $file);
                $this->write("\t* Guarda el orden\n", $file);
                $this->write("\t*/\n", $file);
                $this->write("\tfunction saveOrder()\n", $file);
                $this->write("\t{\n", $file);
                $this->write("\t\t\$data = safeGetVar('ordered_table');\n", $file);
                $this->write("\t\t\$" . $module . " = new " . ucfirst($module) . "();\n", $file);
                $this->write("\t\tforeach (\$data as \$order => \$id)\n", $file);
                $this->write("\t\t{\n", $file);
                $this->write("\t\t\tif (\$id <> 'skip_order')\n", $file);
                $this->write("\t\t\t{\n", $file);
                $this->write("\t\t\t\t\$" . $module . "->id = \$id;\n", $file);
                $this->write("\t\t\t\t\$" . $module . "->" . $campo["name_db"] . " = \$order;\n", $file);
                $this->write("\t\t\t\t\$" . $module . "->save();\n", $file);
                $this->write("\t\t\t}\n", $file);
                $this->write("\t\t}\n", $file);
                $this->write("\t}\n\n", $file);
                break;
        }
    }

    /**
     * Cierra la clase
     * @param file $file 
     */
    private function cerrarClase($file)
    {
        $this->write("}", $file);
    }

    /**
     * Escribe el codigo para guardar un campo en la base de datos
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function escribirCampo($module, $campo, $file)
    {
        switch ($campo["type"])
        {
            case '3': //fecha
                $this->write("\t\t\t\t\$" . $module . "->" . $campo["name_db"] . " = DateTimeValueLib::toMySqlDate(safePostVar('" . $campo["name_db"] . "'));\n", $file);
                break;
             case '4': //fecha y hora
                $this->write("\t\t\t\t\$" . $module . "->" . $campo["name_db"] . " = DateTimeValueLib::toMySqlDateAndTime(safePostVar('" . $campo["name_db"] . "'));\n", $file);
                break;
            case '6': //imagen
                $this->write("\t\t\t\t\$oImage = new IUGOImage(" . ucfirst($module) . "::original_path);\n", $file);
                $this->write("\t\t\t\t\$upl = \$oImage->uploadImage(\$_FILES);\n", $file);
                $this->write("\t\t\t\tif (\$upl['result'] === true)\n", $file);
                $this->write("\t\t\t\t{\n", $file);
                $this->write("\t\t\t\t\t\$oImage->duplicateImage(" . strtoupper($module) . "_PIC_ORIGINAL," . strtoupper($module) . "_PIC_IMG);\n", $file);
                $this->write("\t\t\t\t\tif(\$id != '')\n", $file);
                $this->write("\t\t\t\t\t{\n", $file);
                $this->write("\t\t\t\t\t\t\$this->borrar_imagen_viejas(\$id);\n", $file);
                $this->write("\t\t\t\t\t}\n", $file);
                $this->write("\t\t\t\t\t\$" . $module . "->" . $campo["name_db"] . " = \$oImage->getOriginal_name();\n", $file);
                $this->write("\t\t\t\t\t\$" . $module . "->original_width = \$oImage->getOriginal_width();\n", $file);
                $this->write("\t\t\t\t\t\$" . $module . "->original_height = \$oImage->getOriginal_height();\n", $file);
                $this->write("\t\t\t\t}\n", $file);
                $this->write("\t\t\t\telseif(\$_FILES['image']['error'] != \"4\")\n", $file);
                $this->write("\t\t\t\t{\n", $file);
                $this->write("\t\t\t\t\tSession::instance()->setFlash('ERROR: '.\$upl['error'],'error');\n", $file);
                $this->write("\t\t\t\t\tredirectAction('/admin/" . $module . "/index/',true);\n", $file);
                $this->write("\t\t\t\t}\n", $file);
                break;
            case '7': //archivo
                $this->write("\t\t\t\tif (!empty(\$_FILES)) {\n", $file);
                $this->write("\t\t\t\t\t\$upload = new IUGOFileUpload();\n", $file);
                $this->write("\t\t\t\t\t\$upload->allow('pdf');\n", $file);
                $this->write("\t\t\t\t\t\$upload->set_path(" . strtoupper($module) . "_FILE);\n", $file);
                $this->write("\t\t\t\t\t\$upload->set_max_size(MAX_UPLOAD_FILESIZE*100000);\n", $file);
                $this->write("\t\t\t\t\t\$filename = \$upload->upload(\$_FILES['" . $campo["name_db"] . "']);\n", $file);
                $this->write("\t\t\t\t\tif (\$upload->is_error()) {\n", $file);
                $this->write("\t\t\t\t\t\t//Session::instance()->setFlash('ERROR: '.\$upload->get_error(),'error');\n", $file);
                $this->write("\t\t\t\t\t\t//redirectAction('/admin/" . $module . "/index/',true);\n", $file);
                $this->write("\t\t\t\t\t}else{\n", $file);
                $this->write("\t\t\t\t\t\tunlink(" . strtoupper($module) . "_FILE.\$" . $module . "->" . $campo["name_db"] . ");\n", $file);
                $this->write("\t\t\t\t\t\t\$" . $module . "->" . $campo["name_db"] . " = \$filename;\n", $file);
                $this->write("\t\t\t\t\t}\n", $file);
                $this->write("\t\t\t\t}\n", $file);
                break;
            case '8': //checkbox
                $this->write("\t\t\t\t\$" . $campo["name_db"] . " = 0;\n", $file);
                $this->write("\t\t\t\tif(safePostVar('" . $campo["name_db"] . "') == 1)\n", $file);      
                $this->write("\t\t\t\t{\n", $file); 
                $this->write("\t\t\t\t\t\$" . $campo["name_db"] . " = 1;\n", $file); 
                $this->write("\t\t\t\t}\n", $file); 
                $this->write("\t\t\t\t\$" . $module . "->" . $campo["name_db"] . " = \$" . $campo["name_db"] . ";\n", $file); 
                break;
            case '9': //password
                $this->write("\t\t\t\tif(safePostVar('" . $campo["name_db"] . "'))\n", $file);
                $this->write("\t\t\t\t{\n", $file);
                $this->write("\t\t\t\t\t\$" . $module . "->" . $campo["name_db"] . " = getPasswordHash( getPasswordSalt(), safePostVar('" . $campo["name_db"] . "'));\n", $file);
                $this->write("\t\t\t\t}\n", $file);
                break;
            default: //texto
                $this->write("\t\t\t\t\$" . $module . "->" . $campo["name_db"] . " = safePostVar('" . $campo["name_db"] . "');\n", $file);
                break;
        }
    }

}