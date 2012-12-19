<?php
include("includes/inflection.class.php"); 
include("includes/auxiliares.php"); 
include("clases/Module.php");

global $irregularWords;
$irregularWords = array();
$inflection = new Inflection(); 

//$tabla = array(
//                "table" => 
//                        array(   
//                            "name_table" => "page_image",
//                            "name_table_plural" => $inflection->pluralize("page_image"),
//                            "name_view" => "image",
//                            "name_view_plural" => $inflection->pluralize("image")
//                        ),
//                "action" => 
//                        array(   
//                            "table" => "1",
//                            "model" => "1",
//                            "controller" => "1",
//                            "view" => "1",
//                            "template" => "1",
//                            "js" => "1"
//                        ),        
//                "columns" => 
//                        array(
//                            array(
//                                "name_db"=>"nombre",
//                                "name_view"=>"name image",
//                                "type"=>"1",
//                                "length"=>"100",
//                                "required"=>"0",
//                                "hidden"=>"0"),
//                            array(
//                                "name_db"=>"imagen",
//                                "name_view"=>"imagen",
//                                "type"=>"6",
//                                "length"=>"255",
//                                "required"=>"1",
//                                "hidden"=>"0"),                                    
//                            array(
//                                "name_db"=>"orden",
//                                "name_view"=>"orden",
//                                "type"=>"10",
//                                "length"=>"11",
//                                "required"=>"0",
//                                "hidden"=>"1")
//                            )
//                );

$tabla = array();
//datos generales
$tabla["table"] ['name_table'] = safePostVar('nombreTabla');
$tabla["table"] ['name_table_plural'] = $inflection->pluralize(safePostVar('nombreTabla'));
$tabla["table"] ['name_view'] = safePostVar('nombreVista');
$tabla["table"] ['name_view_plural'] = $inflection->pluralize(safePostVar('nombreVista'));
//acciones
$tabla["action"] ['table'] = safePostVar('crear_tabla','0');
$tabla["action"] ['model'] = safePostVar('crear_modelo','0');
$tabla["action"] ['controller'] = safePostVar('crear_controller','0');
$tabla["action"] ['view'] = safePostVar('crear_vista','0');
$tabla["action"] ['template'] = safePostVar('crear_template','0');
$tabla["action"] ['js'] = safePostVar('crear_js','0');
//columnas
for($cont = 1; $cont <= safePostVar('numLineas'); $cont++)
{
    $tabla["columns"] [$cont] ['name_db'] = $_POST['name_db'][$cont];
    $tabla["columns"] [$cont] ['name_view'] = $_POST['name_view'][$cont];   
    $tabla["columns"] [$cont] ['type'] = $_POST['type'][$cont];
    $tabla["columns"] [$cont] ['length'] = $_POST['length'][$cont];    
    if(isset($_POST['required'][$cont]))
    {
        $tabla["columns"] [$cont] ['required'] = $_POST['required'][$cont];
    }
    else
    {
        $tabla["columns"] [$cont] ['required'] = 0;
    }
    if(isset($_POST['hidden'][$cont]))
    {
        $tabla["columns"] [$cont] ['hidden'] = $_POST['hidden'][$cont];
    }
    else
    {
        $tabla["columns"] [$cont] ['hidden'] = 0;
    }    
}

$module = new Module();
$module->create($tabla);

header( 'Location: tablas.php' );
?>
