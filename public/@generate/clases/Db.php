<?php

class Db extends Writer
{

    protected $_conn;
    public $_db;

    function __construct()
    {
        $this->_conn = mysql_connect(DB_HOST_ROOT, DB_USER_ROOT, DB_PASSWORD_ROOT);
        if (!$this->_conn)
        {
            die("Configure los datos del usuario root del manejador de base de datos");
        }
        //nombre de la bd
        $directorio = opendir(PATH_DB);
        while ($archivo = readdir($directorio))
        {
            if ($archivo != "" && $archivo != "." && $archivo != ".." && $archivo != ".svn")
            {
                $name_db = explode(".", $archivo);
                $this->_db = $name_db[0];
            }
        }
        closedir($directorio); 
    }

    function exists_db($data_base)
    {
        $db_selected = mysql_select_db($data_base, $this->_conn);
        if (!$db_selected)
        {
            return false;
        } 
        else
        {
            return true;
        }
    }

    function create_db($data_base)
    {
        $this->_db = $data_base;
        
        $db = "CREATE DATABASE `" . $this->_db . "`;";
        $this->execute_sql($db);

        $this->create_import($db . "\n");
    }

    function create_user_db()
    {
        $usuario = "CREATE USER '" . $this->_db . "'@'localhost' IDENTIFIED BY '" . $this->_db . "';";
        $permisos = "GRANT ALL PRIVILEGES ON `" . $this->_db . "` . * TO '" . $this->_db . "'@'localhost' IDENTIFIED BY '" . $this->_db . "' WITH GRANT OPTION ;";

        $this->execute_sql($usuario);
        $this->execute_sql($permisos);

        $this->create_import($usuario . "\n" . $permisos . "\n");
    }

    function create_table_users()
    {
        $tabla_users = "CREATE TABLE `" . $this->_db . "`.`users` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(id),usuario varchar(20) NOT NULL,clave varchar(200) NOT NULL,nombre varchar(100) NOT NULL,UNIQUE KEY usuario (usuario));";
        $this->execute_sql($tabla_users);

        $insert_user = "INSERT INTO `" . $this->_db . "`.`users` (`id`, `usuario`, `clave`, `nombre`) VALUES (NULL, 'admin', '53bf23ae4ab78054f4331809504de38a23fc2aa0456d296683248ec34d2c056b72d80bcfcaaa6b86f5ed02484773562fee8734fc6878ab124eb304c37e645694b103c7ba', 'Administrador');";
        $this->execute_sql($insert_user);

        $this->create_import($tabla_users . "\n" . $insert_user . "\n");
    }

    function delete_db()
    {
        $drop_user = "DROP USER '" . $this->_db . "'@'localhost';";
        $this->execute_sql($drop_user);

        $drop_db = "DROP DATABASE `" . $this->_db . "`";
        $this->execute_sql($drop_db);

        unlink(PATH_DB . $this->_db . ".sql");
        unlink(PATH_CONFIG . "config.php");
    }

    function create_import($sql)
    {

        $file_path = PATH_DB . $this->_db . ".sql";
        $file = fopen($file_path, 'a+') or die("can't open file");

        fputs($file, $sql);

        fclose($file);
        chmod($file_path, 0777);
    }

    function create_config()
    {
        $file_path = PATH_CONFIG . "config.php";
        $file = fopen($file_path, 'w') or die("can't open file");
        
        $this->write("<?php\n", $file);
        $this->write("define('DEVELOPMENT_ENVIRONMENT',true);\n", $file);
        $this->write("define('LOG_ERRORS',true);\n\n", $file);
        
        $this->write("define('DB_NAME', '" . $this->_db . "');\n", $file);
        $this->write("define('DB_USER', '" . $this->_db . "');\n", $file);
        $this->write("define('DB_PASSWORD', '" . $this->_db . "');\n", $file);
        $this->write("define('DB_HOST', 'localhost');\n", $file);
        $this->write("define('APP_DIR', '/" . $this->_db . "');\n", $file);
        $this->write("define('BASE_PATH', 'http://localhost'.APP_DIR);\n\n", $file);
        
        $this->write("define('TITLE','CMS');\n", $file);
        $this->write("define('PROJECT_TITLE','Project title');\n", $file);
        $this->write("define('CONTACT_EMAIL','pruebas@iugo.com.uy');\n\n", $file);
        
        $this->write("define('ROOT_PATH', realpath(dirname(__FILE__).'/../'). '/application/views/');\n", $file);
        $this->write("define('CACHE_DIR',ROOT.DS.'tmp');\n\n", $file);
        
        $this->write("define('AJUSTE_FECHA', 0);\n", $file);
        $this->write("define('DATE_MYSQL', 'Y-m-d H:i:s');\n", $file);
        $this->write("define('DATE_SHOW', 'd/m/Y');\n", $file);
        $this->write("define('EMPTY_DATETIME', '0000-00-00 00:00:00');\n", $file);
        $this->write("define('PAGINATE_LIMIT', 'All');//limite por defecto en las consultas.\n", $file);
        $this->write("define('URL_FORMAT', \"/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*((:[0-9]{1,5})?\/.*)?$/i\");\n\n", $file);

        $this->write("define('THEME','default'); //Es el theme a usar, nombre de una carpeta en public/css/admin/themes\n\n", $file);
        
        $this->write("define('MSG_GUARDADO','Saved.');\n", $file);
        $this->write("define('MSG_BAD_LOGIN','Incorrect username or password.');\n", $file);
        $this->write("define('MSG_USER_REP','Repeated username.');\n\n", $file);
        
        $this->write("/************************************\n", $file);
        $this->write(" * UPLOAD\n", $file);
        $this->write("*************************************/\n", $file);
        
        $this->write("define('MAX_UPLOAD_FILESIZE',20); //En Mb\n", $file);
        $this->write("define('MAX_UPLOAD_FILESIZE_PIC',2); //En Mb\n", $file);
        $this->write("define('IMG_MAX_WIDTH',980);\n", $file);
        $this->write("define('ABS_PATH',getcwd());\n", $file);
        $this->write("define('UPLOAD_DIR',ABS_PATH.'/uploads/');\n\n", $file);
        
        fclose($file);
        chmod($file_path, 0777);
    }
    
    function install_db()
    {

        $file_path = PATH_DB . $this->_db . ".sql";
        $lineas = file($file_path);

        if ($lineas)
        {
            foreach ($lineas as $num_linea => $linea)
            {
                $this->execute_sql($linea);
            }
        }
    }
    
    function execute_sql($sql)
    {
        mysql_select_db($this->_db, $this->_conn);
        $resultado = mysql_query($sql, $this->_conn) or die(mysql_error());
        return $resultado;
    }
    
    function tables()
    {
        $conn = mysql_connect("localhost", $this->_db, $this->_db);
        mysql_select_db($this->_db, $conn);
        
        $sql = "SHOW TABLES FROM `". $this->_db . "`";
        $resultado = mysql_query($sql, $conn) or die(mysql_error());
        
        $tablas = array();
        while ($row = mysql_fetch_assoc($resultado))
        {
            $tablas[] = $row['Tables_in_'.$_SESSION["date_base"]];
        }
        mysql_free_result($resultado);
        
        return $tablas;
    }

}

?>
