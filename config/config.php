<?php
define('DEVELOPMENT_ENVIRONMENT',true);
define('LOG_ERRORS',true);

define('DB_NAME', 'piamonte');
define('DB_USER', 'piamonte');
define('DB_PASSWORD', 'piamonte');
define('DB_HOST', 'localhost');
define('APP_DIR', '/piamonte');
define('BASE_PATH', 'http://localhost'.APP_DIR);

define('TITLE','CMS');
define('PROJECT_TITLE','Project title');
define('CONTACT_EMAIL','pruebas@iugo.com.uy');

define('ROOT_PATH', realpath(dirname(__FILE__).'/../'). '/application/views/');
define('CACHE_DIR',ROOT.DS.'tmp');

define('AJUSTE_FECHA', 0);
define('DATE_MYSQL', 'Y-m-d H:i:s');
define('DATE_SHOW', 'd/m/Y');
define('EMPTY_DATETIME', '0000-00-00 00:00:00');
define('PAGINATE_LIMIT', 'All');//limite por defecto en las consultas.
define('URL_FORMAT', "/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*((:[0-9]{1,5})?\/.*)?$/i");

define('THEME','default'); //Es el theme a usar, nombre de una carpeta en public/css/admin/themes

define('MSG_GUARDADO','Saved.');
define('MSG_BAD_LOGIN','Incorrect username or password.');
define('MSG_USER_REP','Repeated username.');

/************************************
 * UPLOAD
*************************************/
define('MAX_UPLOAD_FILESIZE',20); //En Mb
define('MAX_UPLOAD_FILESIZE_PIC',2); //En Mb
define('IMG_MAX_WIDTH',980);
define('ABS_PATH',getcwd());
define('UPLOAD_DIR',ABS_PATH.'/uploads/');

define('IMAGES_UPLOAD_DIR',ABS_PATH.'/uploads/images/');
define('IMAGES_DIR',BASE_PATH.'/uploads/images/');

