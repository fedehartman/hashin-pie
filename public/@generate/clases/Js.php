<?php

class Js extends Writer
{
    /**
     * Crea los js para las vistas
     */
    public function create($tabla)
    {
        $module = $tabla["table"]["name_table"];
        $campos = $tabla["columns"];
        
        $this->crearFolder($module, $campos);
        $this->crearIndex($module, $campos);
        $this->crearAdd($module, $campos);
        $this->crearEdit($module, $campos);
    }    
    
    private function crearFolder($module)
    {
        if(!is_dir(PATH_JS.$module))
        { 
            mkdir(PATH_JS.$module, 0777); 
            chmod(PATH_JS.$module, 0777);
        }
    }

    /**
     *  Crea el js para index
     * 
     * @param string $module
     */
    private function crearIndex($module, $campos)
    {
        $file_path = PATH_JS.$module."/index.js";
        $file = fopen($file_path, 'w') or die("can't open file");
        
        $this->write("function delete_row(id)\n", $file);
        $this->write("{\n", $file);
        $this->write("\tif(confirm('Are you sure you want to delete this row?'))\n", $file);
        $this->write("\t{\n", $file);
        $this->write("\t\t\$.get(BASE_PATH+\"/admin/" . $module . "/delete/&id%5B%5D=\"+id,function(data) {\n", $file);
        $this->write("\t\t\tif(data=='ok')\n", $file);
        $this->write("\t\t\t{\n", $file);
        $this->write("\t\t\t\t\$('#'+id).remove();\n", $file);
        $this->write("\t\t\t\tstripTables();\n", $file);
        $this->write("\t\t\t}\n", $file);
        $this->write("\t\t\telse\n", $file);
        $this->write("\t\t\t{\n", $file);
        $this->write("\t\t\t\talert(data);\n", $file);
        $this->write("\t\t\t}\n", $file);
        $this->write("\t\t});\n", $file);
        $this->write("\t}\n", $file);
        $this->write("}\n\n", $file);
        
        $this->write("function deleteSelected()\n", $file);
        $this->write("{\n", $file);
        $this->write("\tif (\$('input.checkbox_delete:checkbox:checked').size() == 0 )\n", $file);
        $this->write("\t{\n", $file);
        $this->write("\t\talert ('No row selected');\n", $file);
        $this->write("\t\treturn false;\n", $file);
        $this->write("\t}\n", $file);
        $this->write("\t\tif(confirm('Are you sure you want to delete all selected rows?'))\n", $file);
        $this->write("\t\t{\n", $file);
        $this->write("\t\t\tvar urlStr = '';\n", $file);
        $this->write("\t\t\t\$('input.checkbox_delete:checkbox:checked').each(function(index) {\n", $file);
        $this->write("\t\t\t\turlStr+= '&id%5B%5D='+$(this).val();\n", $file);
        $this->write("\t\t\t});\n", $file);
        $this->write("\t\t\t\$.get(BASE_PATH+\"/admin/" . $module . "/delete/\"+urlStr,function(data) {\n", $file);
        $this->write("\t\t\t\twindow.location.reload();\n", $file);
        $this->write("\t\t\t});\n", $file);        
        $this->write("\t\t}\n", $file);
        $this->write("}\n\n", $file);
        
        //TODO verificar todo esto
        foreach ($campos as $campo)
        {
            $this->escribirFuncionIndex($module, $campo, $file);
        } 
        
        $this->write("\$(document).ready(function(){\n", $file);
        foreach ($campos as $campo)
        {
            $this->escribirDocumentReadyIndex($module, $campo, $file);
        } 
        $this->write("});\n", $file);
        
        fclose($file);
        chmod($file_path, 0777);
    }

    /**
     *  Crea la vista add
     * 
     * @param string $module
     * @param array $campos 
     */
    private function crearAdd($module, $campos)
    {
        $file_path = PATH_JS.$module."/add.js";
        $file = fopen($file_path, 'w') or die("can't open file");
        
        $this->write("$(document).ready(function(){\n", $file);
        $this->write("\t$('#form_" . $module . "').submit( function () { return validateGeneric('form_" . $module . "'); } );\n", $file);
        foreach ($campos as $campo)
        {
            $this->escribirDocumentReadyAddEdit($module, $campo, $file);
        } 
        $this->write("});\n\n", $file);   
        
        foreach ($campos as $campo)
        {
            $this->escribirFuncionAddEdit($module, $campo, $file);
        } 
        
        fclose($file);
        chmod($file_path, 0777);
    }
    
    /**
     *  Crea la vista add
     * 
     * @param string $module
     * @param array $campos 
     */
    private function crearEdit($module, $campos)
    {
        $file_path = PATH_JS.$module."/edit.js";
        $file = fopen($file_path, 'w') or die("can't open file");
        
        $this->write("$(document).ready(function(){\n", $file);
        $this->write("\t$('#form_" . $module . "').submit( function () { return validateGeneric('form_" . $module . "'); } );\n", $file);
        foreach ($campos as $campo)
        {
            $this->escribirDocumentReadyAddEdit($module, $campo, $file);
        }
        $this->write("});\n\n", $file);       
        
        foreach ($campos as $campo)
        {
            $this->escribirFuncionAddEdit($module, $campo, $file);
        }
        
        fclose($file);     
        chmod($file_path, 0777);
    }
    
    /**
     * Escribe la funcion en el index para la vista
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function escribirFuncionIndex($module, $campo, $file)
    {
        switch ($campo["type"])
        {
            case '6': //imagen
                $this->write("function openCrop(id) {\n", $file);
                $this->write("\twindow.open(BASE_PATH+'/admin/" . $module . "/crop/'+id,\"crop_\"+id,\"menubar=0,resizable=1,scrollbars=1,width=900,height=650\");\n", $file);
                $this->write("\treturn true;\n", $file);
                $this->write("}\n\n", $file);
                break;
        }
    }
    
    /**
     * Escribe el document ready del index para la vista
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function escribirDocumentReadyIndex($module, $campo, $file)
    {
        switch ($campo["type"])
        {
            case '6': //imagen
                $this->write("\t\$(\"a[rel='open_colorbox']\").colorbox();\n", $file);
                break;
            case '10': //ordenar por drag and drop
                $this->write("\t\$('.dragable_tbl').tableDnD({\n", $file);
                $this->write("\t\tonDrop: function(table, row) {\n", $file);
                $this->write("\t\t\tstripTables();\n", $file);
                $this->write("\t\t\t\$.get(BASE_PATH+\"/admin/" . $module . "/saveOrder/&\"+\$.tableDnD.serialize());\n", $file);
                $this->write("\t\t}\n", $file);
                $this->write("\t});\n", $file);
                break;
        }
    }
    
    /**
     * Escribe la funcion en el add o edit para la vista
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function escribirFuncionAddEdit($module, $campo, $file)
    {
        switch ($campo["type"])
        {
            case '9': //password
                $this->write("function modificarPass() {\n", $file);
                $this->write("\t$('#mensaje_pass').hide();\n", $file);
                $this->write("\t$('#cont_pass').show();\n", $file);
                $this->write("}\n", $file);
                break;
        }
    }
    
    /**
     * Escribe el document ready del add o edit para la vista
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function escribirDocumentReadyAddEdit($module, $campo, $file)
    {
        switch ($campo["type"])
        {
            case '6': //imagen
                $this->write("\t\$(\"a[rel='open_colorbox']\").colorbox();\n", $file);
                break;
            case '3': //fecha
                $this->write("\t\$(\"#" . $campo["name_db"] . "\").datepicker({ dateFormat: 'dd/mm/yy' });\n", $file);
                break;
            case '4': //fecha y hora
                $this->write("\t\$(\"#" . $campo["name_db"] . "\").datepicker({ dateFormat: 'dd/mm/yy' });\n", $file);
                break;
        }
    }
}