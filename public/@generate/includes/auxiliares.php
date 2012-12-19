<?php

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
?>