<?php

/**
* function to emulate the register_globals setting in PHP
* for all of those diehard fans of possibly harmful PHP settings :-)
* @author Ruquay K Calloway
* @param string $order order in which to register the globals, e.g. 'egpcs' for default
*/
function register_globals($order = 'egpcs')
{
    // define a subroutine
    if(!function_exists('register_global_array'))
    {
        function register_global_array(array $superglobal)
        {
            foreach($superglobal as $varname => $value)
            {
                global $$varname;
                $$varname = $value;
            }
        }
    }

    $order = explode("\r\n", trim(chunk_split($order, 1)));
    foreach($order as $k)
    {
        switch(strtolower($k))
        {
            case 'e':    register_global_array($_ENV);        break;
            case 'g':    register_global_array($_GET);        break;
            case 'p':    register_global_array($_POST);        break;
            case 'c':    register_global_array($_COOKIE);    break;
            case 's':    register_global_array($_SERVER);    break;
        }
    }
}

function toDataMySQL($data) {
	$data = substr($data,6,4).'-'.substr($data,3,2).'-'.substr($data,0,2);
	return($data);
}

function fromDataMySQL($data) {
	if ( ( $data == '0000-00-00 00:00:00' ) || ( $data == '00/00/0000' ) || ( $data == '' ) ) {
			$data = '-';
	} else {
			$data = substr($data,8,2).'/'.substr($data,5,2).'/'.substr($data,0,4);
	}
	return($data);
}

function fromMongoDB($text) {
	$text = substr($text, 1);
        return($text);
}

function toTelefoneMySQL($telefone) {
	$telefone = substr($telefone,1,2).''.substr($telefone,5,5).''.substr($telefone,11,4);
	return($telefone);
}


?>
