<?php

include ROOT . DS . 'library' . DS . 'error.class.php';
include ROOT . DS . 'library' . DS . 'classes' . DS .'logger' . DS .'Logger_Entry.class.php';
include ROOT . DS . 'library' . DS . 'classes' . DS .'logger' . DS .'Logger_Session.class.php';
include ROOT . DS . 'library' . DS . 'classes' . DS .'logger' . DS .'Logger_Backend.class.php';
include ROOT . DS . 'library' . DS . 'classes' . DS .'logger' . DS .'Logger.class.php';
include ROOT . DS . 'library' . DS . 'classes' . DS .'logger' . DS .'backend'. DS .'Logger_Backend_File.class.php';
include ROOT . DS . 'library' . DS . 'classes' . DS .'logger' . DS .'backend'. DS .'Logger_Backend_Null.class.php';

require_once ROOT . DS . 'library' . DS . 'classes' . DS .'datetimevalue' . DS .'DateTimeValueLib.class.php';
require_once ROOT . DS . 'library' . DS . 'classes' . DS .'datetimevalue' . DS .'DateTimeValue.class.php';
require_once ROOT . DS . 'library' . DS . 'ParseXml.class.php';
require_once ROOT . DS . 'library' . DS . 'excel.php';
require_once ROOT . DS . 'library' . DS . 'excel-ext.php';

//template power---------------------------------------------------------------------------------------------------
require_once ROOT . DS . 'library' . DS . 'template_power' . DS .'class.TemplatePower.inc.php';
require_once ROOT . DS . 'library' . DS . 'IUGOTemplate.php';
//-----------------------------------------------------------------------------------------------------------------

require_once (ROOT . DS . 'library' . DS . 'enum.php');
require_once (ROOT . DS . 'config'  . DS . 'config.php');
require_once (ROOT . DS . 'config'  . DS . 'routing.php');
require_once (ROOT . DS . 'config'  . DS . 'inflection.php');
require_once (ROOT . DS . 'library' . DS . 'security.php');
require_once (ROOT . DS . 'library' . DS . 'session.class.php');
require_once (ROOT . DS . 'library' . DS . 'shared.php');
require_once (ROOT . DS . 'library' . DS . 'cookie.class.php');