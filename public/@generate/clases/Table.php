<?php

class Table extends Writer
{
    /**
     * Crea los js para las vistas
     */
    public function create($tabla)
    {
        $module = $tabla["table"]["name_table"];
        $module_plural = $tabla["table"]["name_table_plural"];
        $campos = $tabla["columns"];
        
        $this->crearTable($module, $module_plural, $campos);
    }     

    /**
     *  Crea la tabla
     * 
     * @param string $module
     * @param array $campos 
     */
    private function crearTable($module, $module_plural, $campos)
    {
        $date_base = new Db();
        
        $file_path = PATH_DB . $date_base->_db . ".sql";
        $file = fopen($file_path, 'a+') or die("can't open file");
        
        $sql  = "CREATE TABLE `" . $date_base->_db . "`.`" . $module_plural . "` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        foreach ($campos as $campo)
        {            
            $sql .= $this->escribirCampo($campo);
        }        
        $sql .= "`updated_on` DATETIME NULL, `created_on` DATETIME NULL);";        
        
        $date_base->execute_sql($sql);
        
        fputs($file,$sql. "\n");
        
        fclose($file);
        chmod($file_path, 0777);
    }    
    
    /**
     * Escribe el codigo para guardar un campo en la base de datos
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function escribirCampo($campo)
    {
        $required = "NULL";
        if($campo["required"] == 1)
        {
            $required = "NOT NULL";
        }      
        switch ($campo["type"])
        {
            case '1': case '7': case '9': //varchar - archivo - password
                $col = "`" . $campo["name_db"] . "` VARCHAR(" . $campo["length"] . ") " . $required . ",";
                break;
            case '2': case '10': //integer - orden
                $col = "`" . $campo["name_db"] . "` INT(" . $campo["length"] . ") " . $required . ",";
                break;
            case '3': //fecha
                $col = "`" . $campo["name_db"] . "` DATE " . $required . ",";
                break;
            case '4': //fecha y hora
                $col = "`" . $campo["name_db"] . "` DATETIME " . $required . ",";
                break;
            case '5': //texto largo
                $col = "`" . $campo["name_db"] . "` TEXT " . $required . ",";
                break;
            case '6': //imagen
                $col = "`" . $campo["name_db"] . "` VARCHAR(" . $campo["length"] . ") " . $required . ", `original_width` INT(11) " . $required . ", `original_height` INT(11) " . $required . ",";
                break;
            case '8': //checkbox
                $col = "`" . $campo["name_db"] . "` TINYINT(" . $campo["length"] . ") " . $required . ",";
                break;
        }
        return $col;
    }
    
}