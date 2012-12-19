<?php
session_start();
include("includes/config.php");
include("clases/Writer.php");
include("clases/Db.php");

$directorio = opendir(PATH_DB);
while ($archivo = readdir($directorio))
{
    if ($archivo != "" && $archivo != "." && $archivo != ".." && $archivo != ".svn")
    {
        $name_db = explode(".", $archivo);
    }
}
closedir($directorio); 

$date_base = new Db();
$date_base->install_db($name_db[0]);

$_SESSION["date_base"] = $name_db[0];

header( 'Location: tablas.php' );
?>
