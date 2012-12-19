<?php

class Model extends Writer
{    
    /**
     * Crea el modelo con todas sus funciones [getById,completeDelete]
     * @param array $tabla 
     */
    public function create($tabla)
    {
        $module = $tabla["table"]["name_table"];
        $module_plural = $tabla["table"]["name_table_plural"];
        $campos = $tabla["columns"];
        
        $file_path = PATH_MODEL.$module . ".php";
        $file = fopen($file_path, 'w') or die("can't open file");

        $this->definirClase($module, $module_plural, $file);
        $this->definirHasOne($file); 
        $this->definirHasMany($file);             
        foreach ($campos as $campo)
        {
            $this->crearFunciones($module, $campo, $file);
            $this->crearCarpetasArchivo($module, $campo);
        }
        $this->crearCompleteDelete($module, $campos, $file);        
        $this->cerrarClase($file);

        fclose($file);
        chmod($file_path, 0777);
    }

    /**
     *  Crea el inicio del documento y define la clase del modelo
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
        $this->write("* Archivo: " . $module . ".php\n", $file);
        $this->write("****************************************/\n\n", $file);
        $this->write("/*\n", $file);
        $this->write("* Modelo " . ucfirst($module_plural) . "\n", $file);
        $this->write("*/\n\n", $file);

        //*****************
        // Defino la clase
        //*****************
        $this->write("class " . ucfirst($module) . " extends IugoModel\n", $file); // Defino la clase del Controller
        $this->write("{\n", $file);
    }
    
    /**
     * Defino todo los has one
     * @param file $file 
     */
    private function definirHasOne($file)
    {
        //TODO HACER EL HASONE
    }
    
    /**
     * Defino todo los has many
     * @param file $file 
     */
    private function definirHasMany($file)
    {
        //TODO HACER EL HASMANY
    }  
    
    /**
     * Crea las funciones del modelo para los campos dependiendo del tipo
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function crearFunciones($module, $campo, $file)
    {
        switch ($campo["type"])
        {
            case '6': //imagen
                //*****************
                // Funcion getById
                //*****************
                $this->write("\tconst img_width = 100;\n", $file);
                $this->write("\tconst img_height = 100;\n", $file);
                $this->write("\tconst original_path = " . strtoupper($module) . "_PIC_ORIGINAL;\n", $file);
                $this->write("\tconst img_path = " . strtoupper($module) . "_PIC_IMG;\n\n", $file);
                $this->write("\tstatic function getById(\$id)\n", $file);
                $this->write("\t{\n", $file);
                $this->write("\t\t\$" . $module . " = new " . ucfirst($module) . "();\n", $file);
                $this->write("\t\t\$" . $module . "->id = \$id;\n", $file);
                $this->write("\t\t\$result = \$" . $module . "->search();\n", $file);
                $this->write("\t\t\$" . $module . "->loadObject(\$result['" . ucfirst($module) . "']);\n", $file);
                $this->write("\t\treturn \$" . $module . ";\n", $file);
                $this->write("\t}\n\n", $file);
                
                break;
        }
    }
    
    /**
     * Crea los define en el config del mvc y las carpetas donde se van a guardar las imagenes o archivos
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function crearCarpetasArchivo($module, $campo)
    {
        $file_path = PATH_CONFIG . "config.php";
        $file = fopen($file_path, 'a+') or die("can't open file");
        
        switch ($campo["type"])
        {
            case '6': //imagen
                if(!is_dir(PATH_UPLOADS.$module))
                { 
                    mkdir(PATH_UPLOADS.$module, 0777); 
                    chmod(PATH_UPLOADS.$module, 0777);
                    if(!is_dir(PATH_UPLOADS.$module. "/original/"))
                    { 
                        mkdir(PATH_UPLOADS.$module. "/original/", 0777); 
                        chmod(PATH_UPLOADS.$module. "/original/", 0777);
                        fputs($file,"define('" . strtoupper($module) . "_PIC_ORIGINAL',UPLOAD_DIR.'" . $module . "/original/');\n");
                        fputs($file,"define('" . strtoupper($module) . "_PATH_ORIGINAL',str_replace(ABS_PATH, APP_DIR, " . strtoupper($module) . "_PIC_ORIGINAL));\n");
                    }
                    if(!is_dir(PATH_UPLOADS.$module. "/img/"))
                    { 
                        mkdir(PATH_UPLOADS.$module. "/img/", 0777); 
                        chmod(PATH_UPLOADS.$module. "/img/", 0777);
                        fputs($file,"define('" . strtoupper($module) . "_PIC_IMG',UPLOAD_DIR.'" . $module . "/img/');\n");
                        fputs($file,"define('" . strtoupper($module) . "_PATH_IMG',str_replace(ABS_PATH, APP_DIR, " . strtoupper($module) . "_PIC_IMG));\n\n");
                    }
                }        
                else
                {
                    if(!is_dir(PATH_UPLOADS.$module. "/original/"))
                    { 
                        mkdir(PATH_UPLOADS.$module. "/original/", 0777); 
                        chmod(PATH_UPLOADS.$module. "/original/", 0777);
                        fputs($file,"define('" . strtoupper($module) . "_PIC_ORIGINAL',UPLOAD_DIR.'" . $module . "/original/');\n");
                        fputs($file,"define('" . strtoupper($module) . "_PATH_ORIGINAL',str_replace(ABS_PATH, APP_DIR, " . strtoupper($module) . "_PIC_ORIGINAL));\n");
                    }
                    if(!is_dir(PATH_UPLOADS.$module. "/img/"))
                    { 
                        mkdir(PATH_UPLOADS.$module. "/img/", 0777); 
                        chmod(PATH_UPLOADS.$module. "/img/", 0777);
                        fputs($file,"define('" . strtoupper($module) . "_PIC_IMG',UPLOAD_DIR.'" . $module . "/img/');\n");
                        fputs($file,"define('" . strtoupper($module) . "_PATH_IMG',str_replace(ABS_PATH, APP_DIR, " . strtoupper($module) . "_PIC_IMG));\n\n");
                    }
                }        
                break;
            case '7': //archivo
                if(!is_dir(PATH_UPLOADS.$module))
                { 
                    mkdir(PATH_UPLOADS.$module, 0777); 
                    chmod(PATH_UPLOADS.$module, 0777);
                    if(!is_dir(PATH_UPLOADS.$module. "/file/"))
                    { 
                        mkdir(PATH_UPLOADS.$module. "/file/", 0777); 
                        chmod(PATH_UPLOADS.$module. "/file/", 0777);
                        fputs($file,"define('" . strtoupper($module) . "_FILE',UPLOAD_DIR.'" . $module . "/file/');\n");
                        fputs($file,"define('" . strtoupper($module) . "_PATH_FILE',str_replace(ABS_PATH, APP_DIR, " . strtoupper($module) . "_FILE));\n\n");
                    }
                }
                else
                {
                    if(!is_dir(PATH_UPLOADS.$module. "/file/"))
                    { 
                        mkdir(PATH_UPLOADS.$module. "/file/", 0777); 
                        chmod(PATH_UPLOADS.$module. "/file/", 0777);
                        fputs($file,"define('" . strtoupper($module) . "_FILE',UPLOAD_DIR.'" . $module . "/file/');\n");
                        fputs($file,"define('" . strtoupper($module) . "_PATH_FILE',str_replace(ABS_PATH, APP_DIR, " . strtoupper($module) . "_FILE));\n\n");
                    }
                }                
                break;
        }
        
        fclose($file);
        chmod($file_path, 0777);
    }
    
    /**
     * Crea las funciones del modelo para los campos dependiendo del tipo
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function crearCompleteDelete($module, $campos, $file)
    {
        $this->write("\tfunction completeDelete()\n", $file);
        $this->write("\t{\n", $file);
        foreach ($campos as $campo)
        {
            switch ($campo["type"])
            {
                case '6': //imagen
                    $this->write("\t\tunlink(" . strtoupper($module) . "_PIC_ORIGINAL . \$this->" . $campo["name_db"] . ");\n", $file);
                    $this->write("\t\tunlink(" . strtoupper($module) . "_PIC_IMG . \$this->" . $campo["name_db"] . ");\n", $file);
                    break;
                case '7': //archivo
                    $this->write("\t\tunlink(" . strtoupper($module) . "_FILE . \$this->" . $campo["name_db"] . ");\n", $file);
                    break;
            }
        }
        $this->write("\t\t\n", $file);
        $this->write("\t\t\$this->delete();\n", $file);
        $this->write("\t}\n", $file);  
    }
    /**
     * Cierra la clase
     * @param file $file 
     */
    private function cerrarClase($file)
    {
        $this->write("}", $file);
    }

}