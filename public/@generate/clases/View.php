<?php

class View extends Writer
{
    /**
     * Crea las vistas para cada vista del controlador
     * @param array $data 
     */
    public function create($tabla)
    {
        $module = $tabla["table"]["name_table"];
        $module_plural = $tabla["table"]["name_table_plural"];
        $module_view = $tabla["table"]["name_view"];
        $module_view_plural = $tabla["table"]["name_view_plural"];
        $campos = $tabla["columns"];
        
        $this->crearFolder($module);
        $this->crearIndex($module, $campos, $module_view_plural);
        $this->crearAdd($module, $campos, $module_view);
        $this->crearEdit($module, $campos, $module_view);
        foreach ($campos as $campo)
        {
            $this->crearViewExtra($module, $campo);
        } 
    }    
    
    private function crearFolder($module)
    {
        if(!is_dir(PATH_VIEW.$module))
        { 
            mkdir(PATH_VIEW.$module, 0777); 
            chmod(PATH_VIEW.$module, 0777);
        }
    }

    /**
     *  Crea la vista index
     * 
     * @param string $module
     * @param array $campos 
     */
    private function crearIndex($module, $campos, $module_view_plural)
    {
        $file_path = PATH_VIEW.$module."/index.html";
        $file = fopen($file_path, 'w') or die("can't open file");
        
        $this->write("<div class=\"block\">\n", $file);
        $this->write("\t<div class=\"secondary-navigation\">\n", $file);
        $this->write("\t\t<ul class=\"wat-cf\">\n", $file);
        $this->write("\t\t\t<li class=\"first active\">{list_link}</li>\n", $file);
        $this->write("\t\t\t<li >{new_link}</li>\n", $file);
        $this->write("\t\t</ul>\n", $file);
        $this->write("\t</div>\n", $file);
        
        $this->write("\t<div class=\"content\">\n", $file);
        $this->write("\t\t<h2 class=\"title\">" . ucfirst($module_view_plural) . "</h2>\n", $file);
        $this->write("\t\t<div class=\"inner\">\n", $file);
        $this->write("\t\t\t{msg_error}\n", $file);
        $this->write("\t\t\t<div style=\"clear:both;\"></div>\n", $file);
        $this->write("\t\t\t<table class=\"stripeMe dragable_tbl table\" id=\"ordered_table\">\n", $file);
        $this->write("\t\t\t\t<tr id=\"skip_order\" class=\"nodrag nodrop\">\n",$file);
        $this->write("\t\t\t\t\t<th class=\"first\"><input type=\"checkbox\" class=\"checkbox toggle\" /></th>\n", $file);  
        $this->write("\t\t\t\t\t<th>Id</th>\n", $file);
        foreach ($campos as $campo)
        {
            if($campo["type"] == "1" || $campo["type"] == "2" || $campo["type"] == "3" || $campo["type"] == "4" || $campo["type"] == "6")
            {
                $this->write("\t\t\t\t\t<th>" . ucfirst($campo["name_view"]) . "</th>\n", $file);
            }            
        }      
        $this->write("\t\t\t\t\t<th width=\"20\" >&nbsp;</th>\n", $file);
        $this->write("\t\t\t\t\t<th width=\"20\" class=\"last\" style=\"width:20px;\">&nbsp;</th>\n", $file);
        $this->write("\t\t\t\t</tr>\n", $file);
        
        $this->write("\t\t\t\t<!-- START BLOCK : listado -->\n", $file);
        $this->write("\t\t\t\t<tr id=\"{id}\">\n", $file);
        $this->write("\t\t\t\t\t<td><input type=\"checkbox\" class=\"checkbox_delete\" name=\"id\" value=\"{id}\" /></td>\n", $file);
        $this->write("\t\t\t\t\t<td>{id}</td>\n", $file);
        foreach ($campos as $campo)
        {
            switch ($campo["type"])
            {
                case '1':case '2':case '3':case '4':
                    $this->write("\t\t\t\t\t<td>{" . $campo["name_db"] . "}</td>\n", $file);
                    break;
                case '6':
                    $this->write("\t\t\t\t\t<td>\n", $file);
                    $this->write("\t\t\t\t\t\t<a href=\"{img}\" rel=\"open_colorbox\" ><img src=\"{img}\" width=\"100\" border=\"0\" alt=\"\" ></a><br />\n", $file);
                    $this->write("\t\t\t\t\t\t{crop_img}\n", $file);
                    $this->write("\t\t\t\t\t</td>\n", $file);
                    break;
            }
        }          
        $this->write("\t\t\t\t\t<td>{edit}</td>\n", $file);
        $this->write("\t\t\t\t\t<td class=\"last\"><a href=\"#\" onclick=\"delete_row('{id}'); return false;\" >{img_delete}</a></td>\n", $file);
        $this->write("\t\t\t\t</tr>\n", $file);
        $this->write("\t\t\t\t<!-- END BLOCK : listado -->\n", $file);
        $this->write("\t\t\t</table>\n", $file);
        $this->write("\t\t\t<div class=\"actions-bar wat-cf\">\n", $file);
        $this->write("\t\t\t\t<div class=\"actions\">\n", $file);
        $this->write("\t\t\t\t\t<button class=\"button\" type=\"submit\" onclick=\"deleteSelected(); return false;\">\n", $file);
        $this->write("\t\t\t\t\t{icon_delete} Delete Selected\n", $file);
        $this->write("\t\t\t\t\t</button>\n", $file);
        $this->write("\t\t\t\t\t{new_btn}\n", $file);
        $this->write("\t\t\t\t</div>\n", $file);
        $this->write("\t\t\t\t{paginado}\n", $file);
        $this->write("\t\t\t</div>\n", $file);
        $this->write("\t\t</div>\n", $file);
        $this->write("\t</div>\n", $file);
        $this->write("</div>\n", $file);
        $this->write("{js_" . $module . "}\n", $file);
        
        fclose($file);
        chmod($file_path, 0777);
    }

    /**
     *  Crea la vista add
     * 
     * @param string $module
     * @param array $campos 
     */
    private function crearAdd($module, $campos, $module_view)
    {
        $file_path = PATH_VIEW.$module."/add.html";
        $file = fopen($file_path, 'w') or die("can't open file");
        
        $this->write("<div class=\"block\">\n", $file);
        $this->write("\t<div class=\"secondary-navigation\">\n", $file);
        $this->write("\t\t<ul class=\"wat-cf\">\n", $file);
        $this->write("\t\t\t<li class=\"first\">{list_link}</li>\n", $file);
        $this->write("\t\t\t<li class=\"active\">{new_link}</li>\n", $file);
        $this->write("\t\t</ul>\n", $file);
        $this->write("\t</div>\n", $file);
        
        $this->write("\t<div class=\"content\">\n", $file);
        $this->write("\t\t{msg_error}\n", $file);
        $this->write("\t\t<h2 class=\"title\">Add new " . ucfirst($module_view) . "</h2>\n", $file);
        $this->write("\t\t<div style=\"clear:both;\"></div>\n", $file);
        $this->write("\t\t<div class=\"inner\">\n", $file);
        $this->write("\t\t\t{start_form}\n", $file);
        $this->write("\t\t\t<input type=\"hidden\" name=\"submit\" id=\"submit\" value=\"true\" />\n", $file);
        $this->write("\t\t\t<div class=\"columns wat-cf\">\n", $file);
        
        $this->write("\t\t\t\t<div class=\"column left\">\n", $file);
        foreach ($campos as $campo)
        {
            $this->escribirCampo($module, $campo, $file);
        } 
        $this->write("\t\t\t\t</div>\n", $file);
        
        $this->write("\t\t\t\t<div class=\"column right\"> \n", $file);
        $this->write("\t\t\t\t</div>\n", $file);
        
        $this->write("\t\t\t</div>\n", $file);
        $this->write("\t\t\t<div class=\"group navform wat-cf\">\n", $file);    
        $this->write("\t\t\t\t{btn_save}\n", $file); 
        $this->write("\t\t\t\t<span class=\"text_button_padding\">or</span>\n", $file); 
        $this->write("\t\t\t\t{close}\n", $file); 
        $this->write("\t\t\t</div>\n", $file); 
        $this->write("\t\t\t{end_form}\n", $file);
        $this->write("\t\t</div>\n", $file); 
        $this->write("\t</div>\n", $file); 
        $this->write("</div>\n", $file); 
        $this->write("{js_" . $module . "}\n", $file);         
        
        fclose($file);
        chmod($file_path, 0777);
    }
    
    /**
     *  Crea la vista add
     * 
     * @param string $module
     * @param array $campos 
     */
    private function crearEdit($module, $campos, $module_view)
    {
        $file_path = PATH_VIEW.$module."/edit.html";
        $file = fopen($file_path, 'w') or die("can't open file");
        
        $this->write("<div class=\"block\">\n", $file);
        $this->write("\t<div class=\"secondary-navigation\">\n", $file);
        $this->write("\t\t<ul class=\"wat-cf\">\n", $file);
        $this->write("\t\t\t<li class=\"first\">{list_link}</li>\n", $file);
        $this->write("\t\t\t<li class=\"active\">{new_link}</li>\n", $file);
        $this->write("\t\t</ul>\n", $file);
        $this->write("\t</div>\n", $file);
        
        $this->write("\t<div class=\"content\">\n", $file);
        $this->write("\t\t{msg_error}\n", $file);
        $this->write("\t\t<h2 class=\"title\">Edit " . ucfirst($module_view) . "</h2>\n", $file);
        $this->write("\t\t<div style=\"clear:both;\"></div>\n", $file);
        $this->write("\t\t<div class=\"inner\">\n", $file);
        $this->write("\t\t\t{start_form}\n", $file);
        $this->write("\t\t\t<input type=\"hidden\" name=\"id\" id=\"id\" value=\"{id}\" />\n", $file);
        $this->write("\t\t\t<input type=\"hidden\" name=\"submit\" id=\"submit\" value=\"true\" />\n", $file);
        $this->write("\t\t\t<div class=\"columns wat-cf\">\n", $file);
        
        $this->write("\t\t\t\t<div class=\"column left\">\n", $file);
        //TODO Hay que verificar el tipo de dato y la longitud para ver q tipo de input se muestra
        foreach ($campos as $campo)
        {
            $this->escribirCampo($module, $campo, $file);
        } 
        $this->write("\t\t\t\t</div>\n", $file);
        
        $this->write("\t\t\t\t<div class=\"column right\"> \n", $file);
        $this->write("\t\t\t\t</div>\n", $file);
        
        $this->write("\t\t\t</div>\n", $file);
        $this->write("\t\t\t<div class=\"group navform wat-cf\">\n", $file);    
        $this->write("\t\t\t\t{btn_save}\n", $file); 
        $this->write("\t\t\t\t<span class=\"text_button_padding\">or</span>\n", $file); 
        $this->write("\t\t\t\t{close}\n", $file); 
        $this->write("\t\t\t</div>\n", $file); 
        $this->write("\t\t\t{end_form}\n", $file);
        $this->write("\t\t</div>\n", $file); 
        $this->write("\t</div>\n", $file); 
        $this->write("</div>\n", $file); 
        $this->write("{js_" . $module . "}\n", $file);         
        
        fclose($file);
        chmod($file_path, 0777);
    }
    
    /**
     * Crea las funciones del template para las vistas dependiendo del tipo del campo
     * 
     * @param string $module
     * @param string $campo
     */
    private function crearViewExtra($module, $campo)
    {
        switch ($campo["type"])
        {
            case '6': //imagen
                $file_path = PATH_VIEW.$module."/crop.html";
                $file = fopen($file_path, 'w') or die("can't open file");
                
                $this->write("<script type=\"text/javascript\" >\n", $file);
                $this->write("\tvar thumb_width = {thumb_width};\n", $file);
                $this->write("\tvar thumb_height = {thumb_height};\n", $file);
                $this->write("\tvar current_large_image_width = {current_large_image_width};\n", $file);
                $this->write("\tvar current_large_image_height = {current_large_image_height};\n", $file);
                $this->write("</script>\n\n", $file);
                
                $this->write("<div align=\"center\">\n", $file);
                $this->write("\t<img src=\"{original_src}\" style=\"float: left; margin-right: 10px; \" id=\"thumbnail\" alt=\"\" width=\"{current_large_image_width}\" />\n", $file);
                $this->write("\t<div style=\"border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden;\" id=\"thumb_cont\" >\n", $file);
                $this->write("\t\t<img src=\"{original_src}\" style=\"position: relative;\" alt=\"Thumbnail Preview\" id=\"thumb\" />\n", $file);
                $this->write("\t</div>\n", $file);                
                $this->write("\t{start_form}\n", $file);                
                $this->write("\t\t<input type=\"hidden\" name=\"x1\" value=\"\" id=\"x1\" />\n", $file);
                $this->write("\t\t<input type=\"hidden\" name=\"y1\" value=\"\" id=\"y1\" />\n", $file);
                $this->write("\t\t<input type=\"hidden\" name=\"x2\" value=\"\" id=\"x2\" />\n", $file);
                $this->write("\t\t<input type=\"hidden\" name=\"y2\" value=\"\" id=\"y2\" />\n", $file);
                $this->write("\t\t<input type=\"hidden\" name=\"w\" value=\"\" id=\"w\" />\n", $file);
                $this->write("\t\t<input type=\"hidden\" name=\"h\" value=\"\" id=\"h\" />\n", $file);
                $this->write("\t\t<div class=\"btn_container\" >\n", $file);
                $this->write("\t\t\t<a href=\"#\" rel=\"open_colorbox\">\n", $file);
                $this->write("\t\t\t\tShow Preview\n", $file);
                $this->write("\t\t\t</a>\n", $file);
                $this->write("\t\t\t<input type=\"submit\" name=\"upload_thumbnail\" value=\"Save image\" id=\"save_thumb\" />\n", $file);
                $this->write("\t\t</div>\n", $file);
                $this->write("\t{end_form}\n", $file);
                $this->write("</div>\n", $file);
                
                fclose($file);
                chmod($file_path, 0777);
                break;
        }
    }
    
    /**
     * Escribe el codigo para mostrar un campo de la base de datos
     * 
     * @param string $module
     * @param string $campo
     * @param file $file 
     */
    private function escribirCampo($module, $campo, $file)
    {
        if($campo["hidden"] == 0)
        {
            $name_db = $campo["name_db"];
            $name_view = ucfirst($campo["name_view"]);            
            $length = $campo["length"];
            $required = "";
            if($campo["required"] == 1)
            {
                $required = "required";
            }            
            
            switch ($campo["type"])
            {
                case '3': case '4'://fecha y fecha - hora
                    $this->write("\t\t\t\t\t<div class=\"group\">\n", $file);
                    $this->write("\t\t\t\t\t\t<label class=\"label\" for=\"" . $name_db . "\" >" . $name_view . ": </label>\n", $file);
                    $this->write("\t\t\t\t\t\t<input type=\"text\" class=\"text_field fecha " . $required . "\" name=\"" . $name_db . "\" id=\"" . $name_db . "\" value=\"{" . $name_db . "}\" maxlength=\"" . $length . "\" />\n", $file);
                    $this->write("\t\t\t\t\t</div>\n", $file);
                    break;
                case '5'://texto largo
                    $this->write("\t\t\t\t\t<div class=\"group\">\n", $file);
                    $this->write("\t\t\t\t\t\t<label class=\"label\" for=\"" . $name_db . "\" >" . $name_view . ": </label>\n", $file);
                    $this->write("\t\t\t\t\t\t<textarea class=\"text_area\" name=\"" . $name_db . "\" id=\"" . $name_db . "\" rows=\"4\" cols=\"50\" >{" . $name_db . "}</textarea>\n", $file);
                    $this->write("\t\t\t\t\t</div>\n", $file);
                    break;
                case '6': //imagen
                    $this->write("\t\t\t\t\t<div class=\"group\" id=\"update_image\">\n", $file);
                    $this->write("\t\t\t\t\t\t<label class=\"label\" for=\"image\" >" . $name_view . ": </label>\n", $file);
                    $this->write("\t\t\t\t\t\t<input type=\"file\" class=\"text_field " . $required . "\" name=\"image\" id=\"image\" value=\"Upload\"/>\n", $file);
                    $this->write("\t\t\t\t\t\t<span class=\"description\">Jpeg, gif or png. Max. filesize {max_upload_filesize}mb.</span>\n", $file);
                    $this->write("\t\t\t\t\t</div>\n", $file);
                    $this->write("\t\t\t\t\t<!-- START BLOCK : image -->\n", $file);
                    $this->write("\t\t\t\t\t<div id=\"actual_image\">\n", $file);
                    $this->write("\t\t\t\t\t\t<p>\n", $file);
                    $this->write("\t\t\t\t\t\t\t<label class=\"label\" for=\"image\" >Uploaded Image: </label>\n", $file);
                    $this->write("\t\t\t\t\t\t\t<input type=\"hidden\" name=\"image\" value=\"{" . $name_db . "}\" />\n", $file);
                    $this->write("\t\t\t\t\t\t\t{img}<br />\n", $file);
                    $this->write("\t\t\t\t\t\t\t<br />\n", $file);
                    $this->write("\t\t\t\t\t\t\t<a href=\"#\" onclick=\"$('#update_image').show();$('#actual_image').hide();return false;\" >Cambiar imagen</a>\n", $file);
                    $this->write("\t\t\t\t\t\t</p>\n", $file);
                    $this->write("\t\t\t\t\t</div>\n", $file);
                    $this->write("\t\t\t\t\t<script type=\"text/javascript\">\n", $file);
                    $this->write("\t\t\t\t\t\t\$('#update_image').hide();\n", $file);
                    $this->write("\t\t\t\t\t</script>\n", $file);
                    $this->write("\t\t\t\t\t<!-- END BLOCK : image -->\n", $file);
                    break;
                case '7': //archivo
                    $this->write("\t\t\t\t\t<div class=\"group\" id=\"update_archivo\">\n", $file);
                    $this->write("\t\t\t\t\t\t<label class=\"label\" for=\"" . $name_db . "\" >" . $name_view . ": </label>\n", $file);
                    $this->write("\t\t\t\t\t\t<input type=\"file\" class=\"text_field " . $required . "\" name=\"" . $name_db . "\" id=\"" . $name_db . "\" value=\"Upload\"/>\n", $file);
                    $this->write("\t\t\t\t\t\t<span class=\"description\">Pdf. Max. filesize {max_upload_filesize_pdf}mb.</span>\n", $file);
                    $this->write("\t\t\t\t\t</div>\n", $file);
                    $this->write("\t\t\t\t\t<!-- START BLOCK : archivo -->\n", $file);
                    $this->write("\t\t\t\t\t<div id=\"actual_archivo\">\n", $file);
                    $this->write("\t\t\t\t\t\t<p>\n", $file);
                    $this->write("\t\t\t\t\t\t\t<label class=\"label\" for=\"" . $name_db . "\" >Uploaded File: </label>\n", $file);
                    $this->write("\t\t\t\t\t\t\t<input type=\"hidden\" name=\"" . $name_db . "\" value=\"{" . $name_db . "}\" />\n", $file);
                    $this->write("\t\t\t\t\t\t\t{ver_archivo}<br />\n", $file);
                    $this->write("\t\t\t\t\t\t\t<br />\n", $file);
                    $this->write("\t\t\t\t\t\t\t<a href=\"#\" onclick=\"$('#update_archivo').show();$('#actual_archivo').hide();return false;\" >Change file</a>\n", $file);
                    $this->write("\t\t\t\t\t\t</p>\n", $file);
                    $this->write("\t\t\t\t\t</div>\n", $file);
                    $this->write("\t\t\t\t\t<script type=\"text/javascript\">\n", $file);
                    $this->write("\t\t\t\t\t\t\$('#update_archivo').hide();\n", $file);
                    $this->write("\t\t\t\t\t</script>\n", $file);
                    $this->write("\t\t\t\t\t<!-- END BLOCK : archivo -->\n", $file);
                    break;
                case '8': //checkbox
                    $this->write("\t\t\t\t\t<div class=\"group\">\n", $file);
                    $this->write("\t\t\t\t\t\t<label class=\"label\" for=\"" . $name_db . "\" >" . $name_view . ": </label>\n", $file);
                    $this->write("\t\t\t\t\t\t<input type=\"checkbox\" class=\"text_field\" name=\"" . $name_db . "\" id=\"" . $name_db . "\" value=\"1\" {sel_" . $name_db . "} />\n", $file);
                    $this->write("\t\t\t\t\t</div>\n", $file);
                    break;
                case '9': //password
                    $this->write("\t\t\t\t\t<div class=\"group\">\n", $file);                
                    $this->write("\t\t\t\t\t\t{mod_pass}\n", $file);
                    $this->write("\t\t\t\t\t</div>\n", $file);
                    $this->write("\t\t\t\t\t<div id=\"cont_pass\" {display_pass}>\n", $file);
                    $this->write("\t\t\t\t\t\t<div class=\"group\">\n", $file);
                    $this->write("\t\t\t\t\t\t\t<label class=\"label\" for=\"" . $name_db . "\" >" . $name_view . ": </label>\n", $file);
                    $this->write("\t\t\t\t\t\t\t<input type=\"password\" class=\"text_field " . $required . "\" name=\"" . $name_db . "\" id=\"" . $name_db . "\" value=\"{" . $name_db . "}\" maxlength=\"" . $length . "\" />\n", $file);
                    $this->write("\t\t\t\t\t\t</div>\n", $file);
                    $this->write("\t\t\t\t\t\t<div class=\"group\">\n", $file);
                    $this->write("\t\t\t\t\t\t\t<label class=\"label\" for=\"" . $name_db . "_2\" >Repeat " . $name_view . ": </label>\n", $file);
                    $this->write("\t\t\t\t\t\t\t<input type=\"password\" class=\"text_field " . $required . "\" name=\"" . $name_db . "_2\" id=\"" . $name_db . "_2\" value=\"{" . $name_db . "}\" maxlength=\"" . $length . "\" />\n", $file);
                    $this->write("\t\t\t\t\t\t</div>\n", $file);
                    $this->write("\t\t\t\t\t</div>\n", $file);
                    break;
                default: //varchar e integer
                    $this->write("\t\t\t\t\t<div class=\"group\">\n", $file);
                    $this->write("\t\t\t\t\t\t<label class=\"label\" for=\"" . $name_db . "\" >" . $name_view . ": </label>\n", $file);
                    $this->write("\t\t\t\t\t\t<input type=\"text\" class=\"text_field " . $required . "\" name=\"" . $name_db . "\" id=\"" . $name_db . "\" value=\"{" . $name_db . "}\" maxlength=\"" . $length . "\" />\n", $file);
                    $this->write("\t\t\t\t\t</div>\n", $file);
                    break;
            }            
        }        
    }
}
