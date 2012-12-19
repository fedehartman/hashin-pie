<?php
session_start();
include("includes/config.php");
include("clases/Writer.php");
include("clases/Db.php");

$date_base = new Db();
$date_base->delete_db($_SESSION["date_base"]);
session_destroy();

unlink(PATH_DB.$_SESSION["date_base"]);

header( 'Location: index.php' );
?>
