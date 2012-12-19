$(document).ready(function(){
    $('#form_user').submit( function () { return validar; } );
});

var  fClave2, fUsername, fName

fClave2 = new LiveValidation('clave2', { validMessage: " ", onlyOnBlur: true});
fClave2.add(Validate.Confirmation, { match: 'clave', failureMessage: "Passwords don't match"} );

fUsername = new LiveValidation('usuario', { validMessage: " ", onlyOnBlur: true});
fUsername.add(Validate.Presence, { failureMessage: "* Required" } );

fName = new LiveValidation('nombre', { validMessage: " ", onlyOnBlur: true});
fName.add(Validate.Presence, { failureMessage: "* Required" } );


function validar(){
    return LiveValidation.massValidate( [ fUsername, fClave2 ] );
}

function modificarPass()
{
    $('#mensaje_pass').hide();
    $('#cont_pass').show();
}
