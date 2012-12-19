<?php
session_start();
include("includes/config.php");
include("clases/Writer.php");
include("clases/Db.php");

$_SESSION["seccion"] = "index";

$instalar = false;
$directorio = opendir(PATH_DB);
while ($archivo = readdir($directorio))
{
    if ($archivo != "" && $archivo != "." && $archivo != ".." && $archivo != ".svn")
    {
        $name_db = explode(".", $archivo);        
        $date_base = new Db();
        $exist_db = $date_base->exists_db($name_db[0]);
        if($exist_db)
        {
            $_SESSION["date_base"] = $name_db[0];
        }
        else
        {
            $instalar = true;
        }    
    }
}
closedir($directorio); 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>IUGOGenerator</title>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <?php include("includes/head.php"); ?>
    </head>

    <body>
        <?php include("includes/header.php"); ?>
        <div class="container">
            <div class="hero-unit">
                <h1>IUGOGenerator</h1>
                <?php if(isset($_SESSION["date_base"])){?>
                <p>Ya tienes tu Base de datos creada, puedes agregar o quitar componentes.</p>
                <p>
                    <a class="btn" href="tablas.php">Ver tablas</a>
                </p>
                <?php }elseif($instalar){?>
                <p>Ya tienes tu Base de datos creada, tienes que instalar la base de datos localmente.</p>
                <p>
                    <a class="btn info" href="install_db.php">Instalar modelo</a>
                </p>
                <?php }else{?>                
                <p>Necesitas crear tu Base de Datos para comenzar a crear su contenido. Hay que tener en cuenta que el 
                    nombre de la Base de Datos tiene que ser el mismo que el nombre de la carpeta que contiene el proyecto</p>
                <p>Nombres de tablas en SINGULAR</p>
                <p>
                    <button data-controls-modal="modal_created_db" data-backdrop="true" data-keyboard="true" class="btn success">Comenzar</button>
                </p>
                <?php }?>
            </div>
            <?php include("includes/footer.php"); ?>
        </div> <!-- /container -->
    </body>
    
    <!-- MODAL PARA CREAR BD -->
    <div id="modal_created_db" class="modal hide fade">
        <div class="modal-header">
            <a href="#" class="close">×</a>
            <h3>Ingrese un nombre para la base de datos</h3>
        </div>
        <div class="modal-body">
            <form action="add_db.php" method="post" name="form_add_db" id="form_add_db">
                <fieldset>
                    <div class="clearfix"> 
                        <div class="input">
                            <input class="name_db" id="name_db" name="name_db" size="30" type="text" onkeypress="return sinEspacios(event);"/>
                        </div> 
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn primary" onclick="$('#form_add_db').submit();">Crear!</a>
            <a href="#" class="btn secondary" onclick="$('.close').click();">Cancelar</a>
        </div>
    </div>
    <!-- FIN MODAL PARA CREAR BD -->
    
    <!-- SCRIPTS -->
    <script src="js/bootstrap-modal.js" type="text/javascript"></script>
    <script type="text/javascript">
        function sinEspacios(evt){
            //asignamos el valor de la tecla a keynum
            if(window.event){// IE
                keynum = evt.keyCode;
            }else{
                keynum = evt.which;
            }
            //comprobamos si se encuentra en el rango
            if(keynum == 32){
                return false;
            }else{
                return true;
            }
        }
    </script>
</html>
