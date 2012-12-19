<?php
session_start();
include("includes/config.php");
include("clases/Writer.php");
include("clases/Db.php");
$_SESSION["seccion"] = "tabla";

$date_base = new Db();
$tablas = $date_base->tables();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>IUGOGenerator - Tablas</title>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <?php include("includes/head.php"); ?>
    </head>

    <body>
        <?php include("includes/header.php"); ?>
        <div class="container">
            <div class="content">
                <div class="page-header">
                    <h1>Base de datos : <?php echo $_SESSION["date_base"]; ?> <a href="delete_db.php" onclick="return confirm('Estas seguro que deseas borrar la base de datos? Se borrara todo el contenido.');" class="btn danger" style="float: right" >Borrar base de datos</a></h1>
                </div>
                <div class="row">
                    <div class="span14">
                        <h2>Tablas <a href="columnas.php" class="btn info" style="float: right">Nueva tabla</a></h2>
                    </div>
                    <div class="span14">
                        <table class="zebra-striped" id="sortTableExample">
                            <thead>
                                <tr>
                                    <th class="header">Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tablas as $tabla){ ?>
                                <tr>
                                    <td><?php echo $tabla;?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php include("includes/footer.php"); ?>
        </div> <!-- /container -->
        <!-- SCRIPTS -->
        <script src="js/jquery.tablesorter.min.js"></script>
        <script >
            $(function() {
                $("table#sortTableExample").tablesorter({ sortList: [[1,0]] });
            });
        </script>
        </body>
</html>
