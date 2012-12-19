<?php
session_start();
include("includes/config.php");
include("clases/Writer.php");
include("clases/Db.php");

$db = $_POST['name_db'];
$date_base = new Db();
$date_base->create_db($db);
$date_base->create_user_db();
$date_base->create_table_users();
$date_base->create_config();

$_SESSION["date_base"] = $db;

header( 'Location: tablas.php' );
?>
