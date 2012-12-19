<?php

class User extends IugoModel {

    /**
     * Funcion que me devuelve "User" para loguearse
     * @param string username usuario para loguearse
     * @return User
     */
    static function getByUsername($username)
    {
        $user = new User();
        $user->where('usuario', $username);
        $result = $user->search();
       
        $user->loadObject($result[0]['User']);
        return $user;
    }
    
    public static $tipos = array("A","U");
	
}