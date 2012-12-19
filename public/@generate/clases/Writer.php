<?php

class Writer
{

    /**
     * Escribe la variable $string en el archivo que recibe por parametro.
     * 
     * @param String $string
     * @param file $file 
     */
    public function write($string, $file)
    {
        fwrite($file, $string);
    }

}