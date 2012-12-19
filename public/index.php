<?php	

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

if (isset($_GET['url'])) {
	$url = $_GET['url'];
}

if($_GET)
{
    $par_url = explode("/", $_GET['url']);
    if( $par_url[0] == "@design")//si quiero ver el maquetado
    {
        if($par_url[1] != "")
        {
            require_once (ROOT . DS . '@design' . DS . 'layout/' . $par_url[1]);
        }
        else
        {
            if(file_exists(ROOT . DS . '@design' . DS . 'layout/index.php'))
            {
                require_once (ROOT . DS . '@design' . DS . 'layout/index.php');
            }
            else
            {
                require_once (ROOT . DS . '@design' . DS . 'layout/index.html');
            }            
        }   
    }
    else
    {
        require_once (ROOT . DS . 'library' . DS . 'bootstrap.php');
    }
}
else
{
    require_once (ROOT . DS . 'library' . DS . 'bootstrap.php');
}

