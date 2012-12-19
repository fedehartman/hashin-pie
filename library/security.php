<?php
// get a new salt - 8 hexadecimal characters long
// current PHP installations should not exceed 8 characters
// on dechex( mt_rand() )
// but we future proof it anyway with substr()
function getPasswordSalt()
{
    return substr( str_pad( dechex( mt_rand() ), 8, '0',
                                           STR_PAD_LEFT ), -8 );
}

// calculate the hash from a salt and a password
function getPasswordHash( $salt, $password )
{
    return $salt . ( hash( 'whirlpool', $salt . $password ) );
}

// compare a password to a hash
function comparePassword( $password, $hash )
{
    $salt = substr( $hash, 0, 8 );
    return $hash == getPasswordHash( $salt, $password );
}
?>