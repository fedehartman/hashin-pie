<?php
session_start();
$_SESSION["seccion"] = "columnas";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>IUGOGenerator - Nueva tabla</title>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <?php include("includes/head.php"); ?>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js" ></script>
        <style type="text/css">
            .ul_form{
                list-style-type: none; 
                float: left;
            }
            .div_form{
                float: left;
            }
        </style>
        <style type="text/css">
            .top-button
            {
                margin-top: 5px;
                margin-right: 1px;
                display: none;
            }

            .borrar-linea
            {
                margin-top: 5px;
            }
        </style>
    </head>

    <body>
        <?php include("includes/header.php"); ?>
        <div class="container">
            <div class="content">
                <div class="page-header">
                    <h1>
                        <div id="nombre-tabla" style="display: inline">Nombre tabla</div> 
                        <div id="nombre-vista" style="display: inline;font-size: 18px;color: #BFBFBF;">Nombre vista</div>
                    </h1>
                </div>

                <button id="agregar-linea" class="btn success pull-right small" onclick="agregarLinea()">Agregar linea</button>

                <div class="row">
                    <div class="span14">
                        <form action="save_rows.php" method="post" id="form_tabla">
                            <fieldset>
                                <div>                                  
                                    <ul class="ul_form">
                                        <li>
                                            <a class="btn" onclick="acciones('tabla')" id="a_tabla">Tabla</a>
                                            <input id="crear_tabla" name="crear_tabla" type="hidden" value="0"/>
                                        </li>
                                    </ul>
                                    <ul class="ul_form">
                                        <li>                                            
                                            <a class="btn" onclick="acciones('modelo')" id="a_modelo">Modelo</a>
                                            <input id="crear_modelo" name="crear_modelo" type="hidden" value="0"/>
                                        </li>
                                    </ul>
                                    <ul class="ul_form">
                                        <li>                                            
                                            <a class="btn" onclick="acciones('controller')" id="a_controller">Contoller</a>
                                            <input id="crear_controller" name="crear_controller" type="hidden" value="0"/>
                                        </li>
                                    </ul>
                                    <ul class="ul_form">
                                        <li>                                            
                                            <a class="btn" onclick="acciones('vista')" id="a_vista">Vistas</a>
                                            <input id="crear_vista" name="crear_vista" type="hidden" value="0"/>
                                        </li>
                                    </ul>
                                    <ul class="ul_form">
                                        <li>                                            
                                            <a class="btn" onclick="acciones('template')" id="a_template">Template</a>
                                            <input id="crear_template" name="crear_template" type="hidden" value="0"/>
                                        </li>
                                    </ul>
                                    <ul class="ul_form">
                                        <li>
                                            <a class="btn" onclick="acciones('js')" id="a_js">Js</a>
                                            <input id="crear_js" name="crear_js" type="hidden" value="0"/>                                            
                                        </li>
                                    </ul>
                                </div>
                                <div id="lineas">
                                    <!-- ACA VAN LAS LINEAS -->
                                </div>

                                <div class="clearfix"></div>
                                <div class="actions ">
                                    <input type="button" class="btn primary" value="Guardar" onclick="validarForm(); return false;"/>
                                </div>
                            </fieldset>
                            <input type="hidden" id="num-lineas" name="numLineas" value="0" />
                            <input type="hidden" id="nombre-tabla-h" name="nombreTabla" /> 
                            <input type="hidden" id="nombre-vista-h" name="nombreVista" /> 
                        </form>
                    </div>
                </div>
            </div>
            <?php include("includes/footer.php"); ?>
        </div> 
        <!-- /container -->

        <!-- Cosas que voy a presisar -->
        <div id="linea-original" style="display: none">
            <div  class="div_form hide" >
                <ul class="ul_form">
                    <li>
                        Nombre de base de datos
                    </li>
                    <li>
                        <input class="span3" id="name_db" name="name_db[]" type="text"/>
                    </li>
                </ul>
                <ul class="ul_form">
                    <li>
                        Nombre para la vista
                    </li>
                    <li>
                        <input class="span3" id="name_view" name="name_view[]" type="text"/>
                    </li>
                </ul>
                <ul class="ul_form">
                    <li>
                        Tipo
                    </li>
                    <li>
                        <select class="span2" name="type[]" id="type">
                            <option value="1">Varchar</option>
                            <option value="2">Integer</option>
                            <option value="3">Date</option>
                            <option value="4">Datetime</option>
                            <option value="5">Texto Largo</option>
                            <option value="6">Imagen</option>                                                
                            <option value="7">Archivo</option>
                            <option value="8">Checkbox</option>
                            <option value="9">Password</option>
                            <option value="10">Orden</option>
                        </select>
                    </li>
                </ul>
                <ul class="ul_form">
                    <li>
                        Longitud / valor
                    </li>
                    <li>
                        <input class="span2" id="length" name="length[]" type="text"/>
                    </li>
                </ul>
                <ul class="ul_form">
                    <li>
                        Obligatorio
                    </li>
                    <li>
                        <input id="required" name="required[]" type="checkbox" value="1"/>
                    </li>
                </ul>
                <ul class="ul_form">
                    <li>
                        Oculto
                    </li>
                    <li>
                        <input id="hidden" name="hidden[]" type="checkbox" value="1"/>
                    </li>
                </ul>
            </div>
        </div>        

    </body>
    <script src="js/bootstrap-twipsy.js" type="text/javascript"></script>
    <script type="text/javascript">    
    
        $(document).ready(function() {
            agregarLinea();
        });
    
        function agregarLinea()
        {
            var lineaOriginal = $('#linea-original').clone(true);
            var numeroLineaActual = parseInt($('#num-lineas').val());
            var numeroLineaNueva = numeroLineaActual + 1;
            $('#num-lineas').val(numeroLineaNueva);
            
            var id = 'linea_'+numeroLineaNueva;
            $(lineaOriginal).find('.div_form').attr('id',id);
            
            //le coloco el numero de la linea en el name para el post
            $(lineaOriginal).find('#'+id+' #name_db').attr('name','name_db['+numeroLineaNueva+']');
            $(lineaOriginal).find('#'+id+' #name_view').attr('name','name_view['+numeroLineaNueva+']');
            $(lineaOriginal).find('#'+id+' #type').attr('name','type['+numeroLineaNueva+']');
            $(lineaOriginal).find('#'+id+' #length').attr('name','length['+numeroLineaNueva+']');
            $(lineaOriginal).find('#'+id+' #required').attr('name','required['+numeroLineaNueva+']');
            $(lineaOriginal).find('#'+id+' #hidden').attr('name','hidden['+numeroLineaNueva+']');
            
            //$(lineaOriginal).append('<div class="clearfix"></div>');
            
            $('#lineas').append($(lineaOriginal).html());
            
            $('#'+'linea_'+numeroLineaNueva).fadeIn();
            
            $("#agregar-linea").html('Agregar linea ('+numeroLineaNueva+')')
            $("#agregar-linea-head").html('Agregar linea ('+numeroLineaNueva+')')
            
            return false;
        }


        $(window).scroll(function () 
        {       
            if($(this).scrollTop() >= 120)
            {
                $("#agregar-linea-head").fadeIn('fast');
            }else
            {
                $("#agregar-linea-head").fadeOut('fast');
            }     
        });
        
        $("#nombre-tabla").click(function()
        {
            if($(this).html() != "Nombre tabla")
            {
                var inputHtml = ' <input type="text" id="nombre-tabla-input" value="'+$(this).html()+'" "/>';
            }
            else
            {
                var inputHtml = ' <input type="text" id="nombre-tabla-input" />';
            }
            $(this).html(inputHtml);  
            $(this).find('#nombre-tabla-input').focus(); 
           
            return false;
        });
       
        $('#nombre-tabla-input').live('focusout',function()
        {
            var val = $(this).val();

            if(val == "")
            {
                var html = "Nombre tabla";
 
            }else
            {
                var html = val; 
            }                       
            $("#nombre-tabla").html(html);             
            $("#nombre-tabla-h").val(val);
        });        
        
        $("#nombre-vista").click(function()
        {
            if($(this).html() != "Nombre vista")
            {
                var inputHtml = ' <input type="text" id="nombre-vista-input" value="'+$(this).html()+'"/>';
            }
            else
            {
                var inputHtml = ' <input type="text" id="nombre-vista-input" />';
            }
            
            $(this).html(inputHtml);           
            $(this).find('#nombre-vista-input').focus();            
           
            return false;
        });
       
        $('#nombre-vista-input').live('focusout',function()
        {
            var val = $(this).val();

            if(val == "")
            {
                var html = "Nombre vista";
 
            }else
            {
                var html = val; 
            }                       
            $("#nombre-vista").html(html);             
            $("#nombre-vista-h").val(val);
        }); 
        
        function acciones(id)
        {
            if($('#a_'+id).attr("class") == "btn")
            {
                $('#a_'+id).attr("class","btn info active");
                $('#crear_'+id).val("1");
            }
            else
            {
                $('#a_'+id).attr("class","btn");
                $('#crear_'+id).val("0");                
            }
        }
        
        function validarForm()
        {
            if($('#nombre-tabla-h').val() != "")
            {
                if($('#nombre-vista-h').val() != "")
                {
                    if($('#crear_tabla').val() == "0" && $('#crear_modelo').val() == "0" && $('#crear_controller').val() == "0" && $('#crear_vista').val() == "0" && $('#crear_template').val() == "0" && $('#crear_js').val() == "0")
                    {
                        alert("Debe seleccionar almenos 1 acción");
                    } 
                    else
                    {
                        $('#form_tabla').submit();
                    }
                } 
                else
                {
                    alert("Debe ingresar un nombre para la vista de la tabla");
                }
            }     
            else
            {
                alert("Debe ingresar un nombre para la tabla");
            }
            
        }
    </script>

</html>

