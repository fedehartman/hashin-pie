<?php

$routing = array(
	'/admin\/(.*?)\/(.*?)/' => '\1/\2',
        '/admin\/(.*?)\/(.*?)' => '\1/\2',
        '/admin/' => 'access/login',//aca le digo q vaya al login
        '/(.*)/(.*)/' => '\1/\2'//agrega site para ver todo el sitio
);

$default['controller'] = 'access';
$default['action'] = 'login';