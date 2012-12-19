<?php

/**
 * shows the data params on screen
 *
 * @param Mixed $data
 * @param boolean $die
 */
function debug($data, $die=true)
{
    if (DEVELOPMENT_ENVIRONMENT)
    {
        if (is_array($data))
        {
            print_r($data);
        } else
        {
            echo $data;
        }

        if ($die)
        {
            die();
        }
    }
}

/** Check if environment is development and display errors * */
function setReporting()
{
    if (DEVELOPMENT_ENVIRONMENT == true)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
    } else
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
    }
}

/** Check for Magic Quotes and remove them * */
function stripSlashesDeep($value)
{
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

function removeMagicQuotes()
{
    if (get_magic_quotes_gpc ())
    {
        $_GET = stripSlashesDeep($_GET);
        $_POST = stripSlashesDeep($_POST);
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

/** Check register globals and remove them * */
function unregisterGlobals()
{
    if (ini_get('register_globals'))
    {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value)
        {
            foreach ($GLOBALS[$value] as $key => $var)
            {
                if ($var === $GLOBALS[$key])
                {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** auxiliares * */

/**
 * Funcion que recibe por post una variable y sino la encuentra la recibe por get. Si no la encuentra coloca el valor por defecto
 * @param string $varName nombre de la variable
 * @param sting $default valor por defecto si no se encuentra la variable
 * @return string valor de la variable
 */
function safePostGetVar($varName, $default=null)
{
    if (isset($_POST[$varName]))
        return $_POST[$varName];
    elseif (isset($_GET[$varName]))
        return $_GET[$varName];
    else
        return $default;
}

function safeSessionVar($varName, $default=null)
{
    if (isset($_SESSION[$varName]))
        return $_SESSION[$varName];
    else
        return $default;
}

/**
 * Funcion que recibe por post una variable. Si no la encuentra coloca el valor por defecto
 * @param string $varName nombre de la variable
 * @param sting $default valor por defecto si no se encuentra la variable
 * @return string valor de la variable
 */
function safePostVar($varName, $default=null)
{
    if (isset($_POST[$varName]))
        return $_POST[$varName];
    else
        return $default;
}

/**
 * Funcion que recibe por get una variable. Si no la encuentra coloca el valor por defecto
 * @param string $varName nombre de la variable
 * @param sting $default valor por defecto si no se encuentra la variable
 * @return string valor de la variable
 */
function safeGetVar($varName, $default=null)
{
    if (isset($_GET[$varName]))
        return $_GET[$varName];
    else
        return $default;
}

function formatNumber($number, $tipo='js')
{
    switch ($tipo)
    {
        case 'js': $separador = '.';
            break;
        case 'php': $separador = ',';
            break;
        case 'mysql': $separador = ',';
            break;
    }
    return number_format($number, 2, $separador, '');
}

function truncate($string, $length, $stopanywhere=false)
{
    //truncates a string to a certain char length, stopping on a word if not specified otherwise.
    if (strlen($string) > $length)
    {
        //limit hit!
        $string = substr($string, 0, ($length - 3));
        if ($stopanywhere)
        {
            //stop anywhere
            $string .= '...';
        } else
        {
            //stop on a word.
            $string = substr($string, 0, strrpos($string, ' ')) . '...';
        }
    }
    return $string;
}

function performAction($controller, $action, $queryString = null, $render = 0)
{
    $controllerName = ucfirst($controller) . 'Controller';
    $dispatch = new $controllerName($controller, $action);
    $dispatch->render = $render;
    return call_user_func_array(array($dispatch, $action), $queryString);
}

function is_ajax_request()
{
    //return safeGetVar('ajax') == 'true';
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest");
}

function is_valid_url($url)
{
    if (str_starts_with($url, '/'))
        return true;
    return preg_match(URL_FORMAT, $url);
}

// devuelve true si el objeto es de instancia class
function instance_of($object, $class)
{
    return $object instanceof $class;
}

// instance_of

/**
 * Redirect to specific URL (header redirection)
 *
 * @access public
 * @param string $to Redirect to this URL
 * @param boolean $die Die when finished
 * @return void
 */
function redirectAction($to, $die = true)
{
    $to = trim($to);
    if (strpos($to, '&amp;') !== false)
    {
        $to = str_replace('&amp;', '&', $to);
    } // if

    $to = APP_DIR . $to;
    header('Location: ' . $to);
    if ($die)
        die();
}

/**
 * Redirect to referer
 *
 * @access public
 * @param string $alternative Alternative URL is used if referer is not valid URL
 * @return null
 */
function redirectActionToReferer($alternative = nulls)
{
    $referer = get_referer();
    header('Location: ' . $referer);
}

/**
 * Return referer URL
 *
 * @param string $default This value is returned if referer is not found or is empty
 * @return string
 */
function get_referer($default = null)
{
    isset($_SERVER['HTTP_REFERER']) ? $result = $_SERVER['HTTP_REFERER'] : $result = $default;
    return $result;
}

// get_referer

function logged_user()
{
    return Session::instance()->getLoggedUser();
}

// logged_user

function routeURL($url)
{
    global $routing;
    foreach ($routing as $pattern => $result)
    {
        if (preg_match($pattern, $url))
        {
            return preg_replace($pattern, $result, $url, 1);
        }
    }
    return ($url);
}

/** Main Call Function * */
function callHook($url)
{
    global $default;

    $queryString = array();

    if (!isset($url))
    {
        $controller = $default['controller'];
        $action = $default['action'];
    } else
    {
        $url = routeURL($url);
        $urlArray = array();
        $urlArray = explode("/", $url);
        $controller = $urlArray[0];
        array_shift($urlArray);
        if (isset($urlArray[0]))
        {
            $action = $urlArray[0];
            array_shift($urlArray);
        } else
        {
            $action = 'index'; // Default Action
        }
        $queryString = $urlArray;
    }

    $controllerName = ucfirst($controller) . 'Controller';

    $dispatch = new $controllerName($controller, $action);

    if ((int) method_exists($controllerName, $action))
    {
        call_user_func_array(array($dispatch, "beforeAction"), $queryString);
        call_user_func_array(array($dispatch, $action), $queryString);
        call_user_func_array(array($dispatch, "afterAction"), $queryString);
    } else
    {
        /* Error Generation Code Here */
    }
}

/* MANEJADORES DE ERROR Y EXCEPCIONES: */

function __production_error_handler($code, $message, $file, $line)
{
    // Skip non-static method called staticly type of error...
    if ($code == 2048)
    {
        return;
    } // if
    Logger::log("Error: $message in '$file' on line $line (error code: $code)", Logger::ERROR);
}

function __production_exception_handler($exception)
{
    Logger::log($exception, Logger::FATAL);
    Logger::saveSession();
}

//========

/** Autocargar clases que se requieren  * */
function __autoload($className)
{
    $current_url = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    $estoyEnAdmin = (strrpos($current_url, "admin") === false) ? 0 : 1;

    if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php'))
    {
        require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . 'admin' . DS . strtolower($className) . '.php') && $estoyEnAdmin)
    {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . 'admin' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . 'front' . DS . strtolower($className) . '.php'))
    {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . 'front' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php'))
    {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'library' . DS . 'errors' . DS . $className . '.class.php'))
    {
        require_once(ROOT . DS . 'library' . DS . 'errors' . DS . $className . '.class.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'templates' . DS . 'admin' . DS . $className . '.php') && $estoyEnAdmin)
    {
        require_once(ROOT . DS . 'application' . DS . 'templates' . DS . 'admin' . DS . $className . '.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'templates' . DS . 'front' . DS . $className . '.php'))
    {
        require_once(ROOT . DS . 'application' . DS . 'templates' . DS . 'front' . DS . $className . '.php');
    } else if ($className == "AccessController")
    {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . 'admin' . DS . strtolower($className) . '.php');
    }else
    {
        echo "No existe la clase " . $className;
        die();
    }
}

/** comprimir la salida con GZip * */
function gzipOutput()
{
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ')
            || false !== strpos($ua, 'Opera'))
    {
        return false;
    }

    $version = (float) substr($ua, 30);
    return (
    $version < 6
    || ($version == 6 && false === strpos($ua, 'SV1'))
    );
}

function verificarLogin($ruta = '/admin/access/logout/')
{
    try
    {
        if (!logged_user())
        {
            redirectAction($ruta, true);
        } else
        {
            return true;
        }
    } catch (Exception $ex)
    {
        Session::instance()->setFlash('ERROR: ' . $ex->getMessage());
    }
}

/*
 * Funcio que verifica que el usuario tenga permisos para el modulo
 */

function verificarPermisos($permiso)
{
    try
    {
        if (logged_user()->tipo != "A")
        {
            if (logged_user()->$permiso != 1)
            {
                echo "No tiene permisos para esta accion";
                die();
            } else
            {
                return true;
            }
        } else
        {
            return true;
        }
    } catch (Exception $ex)
    {
        Session::instance()->setFlash('ERROR: ' . $ex->getMessage());
    }
}

/**
 * Corta el string un string.
 * @param string $text string para cortar
 * @param int $longitud logitud del string
 * @param booleano $trespuntos indica si hay que poner los "..." al final.
 * @return string devuelve el texto cortado
 */
function cortarStr($text, $longitud, $trespuntos=true)
{
    if (strlen($text) > $longitud)
    {
        $text = substr("$text", 0, $longitud);
        if ($trespuntos)
            $text = $text . "...";
    }
    return $text;
}

/**
 *
 * @param array $dbArray el resultado a convertir, de largo N
 * @param string $model el nombre del modelo
 * @param string $field el campo a extraer
 * @return string de la forma "valor_1, valor_2, ... , valor_N" donde valor_i es el valor en el campo $field de la tupla i-esima del array
 */
function dbResultToString($dbArray, $model, $field)
{
    $res = "";
    foreach ($dbArray as $tupla)
    {
        $res .= $tupla[$model][$field] . ", ";
    }
    $res = substr($res, 0, -2);
    return $res;
}

function force_download($filename = '', $data = '')
{
    if ($filename == '' OR $data == '')
    {
            return FALSE;
    }

    // Try to determine if the filename includes a file extension.
    // We need it in order to set the MIME type
    if (FALSE === strpos($filename, '.'))
    {
            return FALSE;
    }

    // Grab the file extension
    $x = explode('.', $filename);
    $extension = end($x);

    // Load the mime types
    @include(APPPATH.'config/mimes'.EXT);

    // Set a default mime if we can't find it
    if ( ! isset($mimes[$extension]))
    {
            $mime = 'application/octet-stream';
    }
    else
    {
            $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
    }

    // Generate the server headers
    if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
    {
            header('Content-Type: "'.$mime.'"');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: ".strlen($data));
    }
    else
    {
            header('Content-Type: "'.$mime.'"');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: ".strlen($data));
    }

    exit($data);
}

/** obtener archivos requeridos * */
$cache = null;
$cache = new Cache();
$inflect = null;
$inflect = new Inflection();

if (LOG_ERRORS)
{
    Logger::setSession(new Logger_Session('default'));
    Logger::setBackend(new Logger_Backend_File(CACHE_DIR . DS . 'logs' . DS . 'log.txt'));

    set_error_handler('__production_error_handler');
    set_exception_handler('__production_exception_handler');
}

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook($url);
?>
