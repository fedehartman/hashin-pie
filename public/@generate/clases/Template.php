<?php

class Template extends Writer
{    
    /**
     * Crea el template con todas sus funciones [index,add,edit]
     * @param array $data 
     */
    public function create($tabla)
    {
        $module = $tabla["table"]["name_table"];
        $module_plural = $tabla["table"]["name_table_plural"];
        $module_view = $tabla["table"]["name_view"];
        $module_view_plural = $tabla["table"]["name_view_plural"];
        $campos = $tabla["columns"];
        
        $file_path = PATH_TEMPLATE."Tpl".ucfirst($module) . ".php";
        $file = fopen($file_path, 'w') or die("can't open file");

        $this->definirClase($module, $module_plural, $file);
        $this->crearIndex($module, $module_plural,$campos , $file);
        $this->crearAdd($module, $campos, $file);
        $this->crearEdit($module, $campos, $file);
        foreach ($campos as $campo)
        {
            $this->crearFuncionesExtra($module, $campo, $file);
        } 
        $this->generateCommonAssigns($module, $module_view_plural, $campos, $file);
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
        $this->write("* Archivo: Tpl" . ucfirst($module) . ".php\n", $file);
        $this->write("****************************************/\n\n", $file);
        $this->write("/*\n", $file);
        $this->write("* Template " . ucfirst($module_plural) . "\n", $file);
        $this->write("*/\n\n", $file);

        //*****************
        // Defino la clase
        //*****************
        $this->write("class Tpl" . ucfirst($module) . "\n", $file); // Defino la clase del Controller
        $this->write("{\n\n", $file);
    }

    /**
     *  Crea la funcion index
     * 
     * @param string $module
     * @param string $module_plural
     * @param array $campos
     * @param file $file 
     */
    private function crearIndex($module, $module_plural, $campos, $file)
    {
        //*****************
        // Funcion getIndex()
        //*****************
        $this->write("\t/**\n", $file);
        $this->write("\t* Funcion que crea un template para la vista index del controlador\n", $file);
        $this->write("\t* @param array \$" . $module_plural . " arreglo de los objetos " . ucfirst($module_plural) . "\n", $file);
        $this->write("\t* @return array \$tpl devuelve el template de la vista index\n", $file);
        $this->write("\t*/\n", $file);
        $this->write("\tfunction getIndex(\$" . $module_plural . ")\n", $file);
        $this->write("\t{\n", $file);
        $this->write("\t\t\$html = new HTML();\n", $file);
        $this->write("\t\t\$tpl = new IUGOTemplate(\"admin/" . $module . "/index.html\");\n", $file);
        $this->write("\t\t\$tpl = \$this->generateCommonAssigns(\$tpl,\$html);\n", $file);
        $this->write("\t\t\$tpl->assign(\"js_" . $module . "\", \$html->includeJs('views/admin/" . $module . "/index'));\n", $file);
        $this->write("\t\t\$tpl->assign(\"msg_error\", Session::instance()->getAndClearFlash());\n\n", $file);
        
        $this->write("\t\tforeach (\$" . $module_plural . " as \$" . $module . ")\n", $file);
        $this->write("\t\t{\n", $file);
        $this->write("\t\t\t\$tpl->newBlock(\"listado\");\n", $file);
        $this->write("\t\t\t\$tpl->assign(\n", $file);
        $this->write("\t\t\t\tarray(\t\"id\"=>\$" . $module . "['" . ucfirst($module) . "']['id'],\n", $file);
        foreach ($campos as $campo)
        {
            if($campo["type"] == "1" || $campo["type"] == "2" || $campo["type"] == "3" || $campo["type"] == "4" || $campo["type"] == "6")
            {
                $this->escribirListado($module, $campo, $file);
            }
        }
        $this->write("\t\t\t\t\t\"edit\"=>\$html->link(\$html->image('admin/16x16/new.png','Edit'),'admin/" . $module . "/edit/'.\$" . $module . "['" . ucfirst($module) . "']['id']),\n", $file);
        $this->write("\t\t\t\t\t\"img_delete\"=>\$html->image('admin/16x16/delete.png','Delete')\n", $file); 
        $this->write("\t\t\t\t));\n", $file);        
        $this->write("\t\t}\n", $file);
        
        $this->write("\t\t\$tpl->gotoBlock(\"_ROOT\");\n\n", $file);
        $this->write("\t\treturn \$tpl;\n", $file);        
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
        // Funcion getAdd()
        //*****************
        $this->write("\t/**\n", $file);
        $this->write("\t* Funcion que crea un template para la vista add del controlador\n", $file);
        $this->write("\t* @param array \$" . $module . " arreglo con el " . ucfirst($module) . "\n", $file);
        $this->write("\t* @return array \$tpl devuelve el template de la vista add\n", $file);
        $this->write("\t*/\n", $file);
        $this->write("\tfunction getAdd(\$" . $module . ")\n", $file);
        $this->write("\t{\n", $file);
        $this->write("\t\t\$html = new HTML();\n", $file);
        $this->write("\t\t\$tpl = new IUGOTemplate(\"admin/" . $module . "/add.html\");\n", $file);
        $this->write("\t\t\$tpl = \$this->generateCommonAssigns(\$tpl,\$html);\n", $file);
        $this->write("\t\t\$tpl->assign(\"js_" . $module . "\", \$html->includeJs('views/admin/" . $module . "/add'));\n", $file);
        $this->write("\t\t\$tpl->assign(\"msg_error\", Session::instance()->getAndClearFlash());\n\n", $file);
        
        $this->write("\t\t\$tpl->assign(\"start_form\",\$html->startForm('form_" . $module . "','form_" . $module . "','/admin/" . $module . "/add', 'post', 'multipart/form-data','form'));\n", $file);
        $this->write("\t\t\$tpl->assign(\"btn_save\",'<button class=\"button\" type=\"submit\">'.\$html->image('admin/icons/tick.png', 'Save').' Save </button>');\n", $file);
        $this->write("\t\t\$tpl->assign(\"close\",\$html->link('Cancel','admin/" . $module . "/index/','','','text_button_padding link_button'));\n", $file);
        $this->write("\t\t\$tpl->assign(\"end_form\",\$html->endForm(''));\n\n", $file);
        foreach ($campos as $campo)
        {
            $this->escribirCampoAdd($module, $campo, $file);
        }
        
        $this->write("\t\treturn \$tpl;\n", $file);      
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
        // Funcion getEdit()
        //*****************
        $this->write("\t/**\n", $file);
        $this->write("\t* Funcion que crea un template para la vista edit del controlador\n", $file);
        $this->write("\t* @param array \$" . $module . " arreglo con el " . ucfirst($module) . "\n", $file);
        $this->write("\t* @return array \$tpl devuelve el template de la vista add\n", $file);
        $this->write("\t*/\n", $file);
        $this->write("\tfunction getEdit(\$" . $module . ")\n", $file);
        $this->write("\t{\n", $file);
        $this->write("\t\t\$html = new HTML();\n", $file);
        $this->write("\t\t\$tpl = new IUGOTemplate(\"admin/" . $module . "/edit.html\");\n", $file);
        $this->write("\t\t\$tpl = \$this->generateCommonAssigns(\$tpl,\$html);\n", $file);
        $this->write("\t\t\$tpl->assign(\"js_" . $module . "\", \$html->includeJs('views/admin/" . $module . "/edit'));\n", $file);
        $this->write("\t\t\$tpl->assign(\"msg_error\", Session::instance()->getAndClearFlash());\n\n", $file);
        
        $this->write("\t\t\$tpl->assign(\"start_form\",\$html->startForm('form_" . $module . "','form_" . $module . "','/admin/" . $module . "/edit', 'post', 'multipart/form-data','form'));\n", $file);
        $this->write("\t\t\$tpl->assign(\"btn_save\",'<button class=\"button\" type=\"submit\">'.\$html->image('admin/icons/tick.png', 'Save').' Save </button>');\n", $file);
        $this->write("\t\t\$tpl->assign(\"close\",\$html->link('Cancel','admin/" . $module . "/index/','','','text_button_padding link_button'));\n", $file);
        $this->write("\t\t\$tpl->assign(\"end_form\",\$html->endForm(''));\n\n", $file);
        
        $this->write("\t\t\$tpl->assign('id',\$" . $module . "['" . ucfirst($module) . "']['id']);\n", $file);        
        foreach ($campos as $campo)
        {
            $this->escribirCampoEdit($module, $campo, $file);
        }
        
        $this->write("\t\treturn \$tpl;\n", $file);      
        $this->write("\t}\n\n", $file);       
    }

    /**
     * Crea las funciones del template para las vistas dependiendo del tipo del campo
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
                $this->write("\t* Vista del crop de foto\n", $file);
                $this->write("\t* @param array \$" . $module . " arreglo del objeto " . ucfirst($module) . "\n", $file);
                $this->write("\t* @return array \$tpl devuelve el template de la vista crop\n", $file);
                $this->write("\t*/\n", $file);
                $this->write("\tfunction getCrop(\$" . $module . ")\n", $file);
                $this->write("\t{\n", $file);
                $this->write("\t\t\$html = new HTML();\n", $file);
                $this->write("\t\t\$tpl= new IUGOTemplate(\"admin/" . $module . "/crop.html\");\n\n", $file);
                $this->write("\t\t\$tpl->assign('original_src', " . strtoupper($module) . "_PATH_ORIGINAL.\$" . $module . "->" . $campo["name_db"] . ");\n\n", $file);
                $this->write("\t\t\$tpl->assign('thumb_width', " . ucfirst($module) . "::img_width);\n", $file);
                $this->write("\t\t\$tpl->assign('thumb_height', " . ucfirst($module) . "::img_height);\n\n", $file);
                $this->write("\t\t\$tpl->assign('current_large_image_width', \$" . $module . "->original_width);\n", $file);
                $this->write("\t\t\$tpl->assign('current_large_image_height', \$" . $module . "->original_height);\n\n", $file);
                $this->write("\t\t\$tpl->assign('start_form',\$html->startForm('thumbnail', 'form_thumbnail', '/admin/" . $module . "/saveThumbnails/'.\$" . $module . "->id, 'post'));\n", $file);
                $this->write("\t\t\$tpl->assign('end_form',\$html->endForm(''));\n\n", $file);
                $this->write("\t\t\$tpl->gotoBlock('_ROOT');\n\n", $file);
                
                $this->write("\t\treturn \$tpl;\n", $file);
                $this->write("\t}\n\n", $file);
                break;
        }
    }
    
    /**
     *  Crea la funcion generateCommonAssigns
     * 
     * @param string $module
     * @param string $module_plural
     * @param array $campos 
     * @param file $file 
     */
    private function generateCommonAssigns($module, $module_view_plural, $campos, $file)
    {
        //*****************
        // Funcion generateCommonAssigns()
        //*****************
        $this->write("\t/**\n", $file);
        $this->write("\t* Genera asignaciones que se van a usar en todos los templates,\n", $file);
        $this->write("\t* como ser cosas del menu, iconos, etc.\n", $file);
        $this->write("\t* @param Object \$tpl un objeto del tipo IUGOTemplate\n", $file);
        $this->write("\t* @param Object \$html un objeto del tipo HTML\n", $file);
        $this->write("\t* @return Object el mismo \$tpl que recibio con mas cosas asignadas\n", $file);
        $this->write("\t*/\n", $file);
        $this->write("\tprivate function generateCommonAssigns(\$tpl,\$html)\n", $file);
        $this->write("\t{\n", $file);
        $this->write("\t\t\$tpl->assign(\n", $file);
        $this->write("\t\t\tarray(\t'icon_add'=>\$html->image('admin/16x16/new.png','Add new'),\n", $file);
        $this->write("\t\t\t\t'new_btn'=>'<button class=\"button\" type=\"submit\" onclick=\"location.href=\''.BASE_PATH.'/admin/" . $module . "/add/\'; return false;\" >'.\$html->image('admin/16x16/new.png','Add new').'Add new</button>',\n", $file);
        $this->write("\t\t\t\t'new_link'=>\$html->link('Add new','admin/" . $module . "/add/'),\n", $file);
        $this->write("\t\t\t\t'list_link'=>\$html->link('List " . ucfirst($module_view_plural) . "','admin/" . $module . "/index/'),\n", $file);
        $this->write("\t\t\t\t'icon_delete'=>\$html->image('admin/icons/cross.png','Delete')\n", $file);
        $this->write("\t\t\t));\n", $file);
        
        $this->write("\t\treturn \$tpl;\n", $file);      
        $this->write("\t}\n\n", $file);    
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
     * Escribe el codigo para mostrar en la vista
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function escribirListado($module, $campo, $file)
    {
        switch ($campo["type"])
        {
            case '3': //fecha
                $this->write("\t\t\t\t\t\"" . $campo["name_db"] . "\"=>DateTimeValueLib::toNormalDate(\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "']),\n", $file);
                break;
            case '4': //fecha y hora
                $this->write("\t\t\t\t\t\"" . $campo["name_db"] . "\"=>DateTimeValueLib::toNormalDateAndTime(\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "']),\n", $file);
                break;
            case '6': //imagen
                $this->write("\t\t\t\t\t\"img\"=> " . strtoupper($module) . "_PATH_IMG.\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "'],\n", $file);
                $this->write("\t\t\t\t\t\"crop_img\"=> \"<a href='#' onclick='openCrop({\$" . $module . "['" . ucfirst($module) . "']['id']});return false;'>Crop Image</a>\",\n", $file);
                break;
            default: //texto
                $this->write("\t\t\t\t\t\"" . $campo["name_db"] . "\"=>\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "'],\n", $file);
                break;      
        }
    }
    
    /**
     * Escribe el codigo para mostrar en la vista
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function escribirCampoAdd($module, $campo, $file)
    {
        switch ($campo["type"])
        {
            case '6': //imagen
                $this->write("\t\t\$tpl->assign('max_upload_filesize',MAX_UPLOAD_FILESIZE_PIC);\n", $file);
                break;
            case '7': //archivo
                $this->write("\t\t\$tpl->assign('max_upload_filesize_pdf',MAX_UPLOAD_FILESIZE);\n", $file);
                break;      
        }
    }
    
    /**
     * Escribe el codigo para mostrar en la vista del editar
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function escribirCampoEdit($module, $campo, $file)
    {
        switch ($campo["type"])
        {
            case '3': //fecha
                $this->write("\t\t\$tpl->assign('" . $campo["name_db"] . "',DateTimeValueLib::toNormalDate(\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "']));\n", $file);
                break;
            case '4': //fecha y hora
                $this->write("\t\t\$tpl->assign('" . $campo["name_db"] . "',DateTimeValueLib::toNormalDateAndTime(\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "']));\n", $file);
                break;
            case '6': //imagen
                $this->write("\t\t\$tpl->assign('max_upload_filesize',MAX_UPLOAD_FILESIZE_PIC);\n", $file);
                $this->write("\t\tif (strlen(\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "']) > 0)\n", $file);
                $this->write("\t\t{\n", $file);
                $this->write("\t\t\t\$tpl->newBlock('image');\n", $file);
                $this->write("\t\t\t\$tpl->assign('" . $campo["name_db"] . "',\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "']);\n", $file);
                $this->write("\t\t\t\$tpl->assign('img', '<a href=\"'." . strtoupper($module) . "_PATH_IMG.\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "'].'\" rel=\"open_colorbox\" ><img src=\"'." . strtoupper($module) . "_PATH_IMG.\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "'].'\" width = \"100\" border = \"0\" alt = \"\" ></a>');\n", $file);
                $this->write("\t\t\t\$tpl->gotoBlock('_ROOT');\n", $file);
                $this->write("\t\t}\n", $file);
                break;
            case '7': //archivo
                $this->write("\t\t\$tpl->assign('max_upload_filesize_pdf',MAX_UPLOAD_FILESIZE);\n", $file);
                $this->write("\t\tif (strlen(\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "']) > 0)\n", $file);
                $this->write("\t\t{\n", $file);
                $this->write("\t\t\t\$tpl->newBlock('archivo');\n", $file);
                $this->write("\t\t\t\$tpl->assign('" . $campo["name_db"] . "',\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "']);\n", $file);
                $this->write("\t\t\t\$tpl->assign('ver_archivo', '<a href=\"'." . strtoupper($module) . "_PATH_FILE.\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "'].'\" target = \"_blank\">Download file</a>');\n", $file);
                $this->write("\t\t\t\$tpl->gotoBlock('_ROOT');\n", $file);
                $this->write("\t\t}\n", $file);
                break;
            case '8': //checkbox
                $this->write("\t\tif(\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "'] == 1)\n", $file);
                $this->write("\t\t{\n", $file);
                $this->write("\t\t\t\$tpl->assign('sel_" . $campo["name_db"] . "','checked=\"checked\"');\n", $file);
                $this->write("\t\t}\n", $file);
                break;
            case '9': //password
                $this->write("\t\t\$tpl->assign('display_pass',\"style='display:none;'\");\n", $file);
                $this->write("\t\t\$tpl->assign('mod_pass','<a id=\"mensaje_pass\" href=\"#\" onclick=\"modificarPass(); return false;\">Cambiar contrase&ntilde;a</a>');\n", $file);
                break;
            default: //texto
                $this->write("\t\t\$tpl->assign('" . $campo["name_db"] . "',\$" . $module . "['" . ucfirst($module) . "']['" . $campo["name_db"] . "']);\n", $file);
                break;   
        }
    }

}