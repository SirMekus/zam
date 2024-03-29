<?php
/**********************************************************************¦
            /\                                                         ¦
           /  \                                                        ¦
          /    \                                                       ¦
         /      \                                                      ¦
        /        \                                                     ¦
	   /          \                                                    ¦
	  /            \                                                   ¦
	 /              \                                                  ¦
	/    SIRMEKUS  	 \                                                 ¦
   /     Coded        \                                                ¦
  /                    \                                               ¦
 /                      \                                              ¦
/_______________________ \                                             ¦
Written By: SIRMEKUS                                                   ¦
@copyright SirMekus 2022                                                        !
                                                                    ¦
************************************************************************/

require_once 'utility/Zam.php';

require_once 'utility/Request.php';

use Sirmekus\App\Zam;

use Sirmekus\App\Request;

use Dotenv\Dotenv;

use Dotenv\Exception\InvalidPathException;

function snakeCase($word)
{
    $newWord = "";

    for($i=0;$i<strlen($word);$i++)
    {
        if($word[$i] == ucwords($word[$i]))
        {
            $newWord .= $i == 0 ? strtolower($word[$i]) : "_".strtolower($word[$i]);
        }
        else
        {
            $newWord .= strtolower($word[$i]);
        }
    }
    return $newWord;
}

function getHttpMesssage($code){
    $http = [
            '200'=>"ok",
            '201'=>"Created",
            '202'=>"Accepted",
            '204'=>"No Content",
            '301'=>"Moved Permanently",
            '308'=>"Permanent Redirect",
            '422'=>"Unprocessable Entity",
            '401'=>"unauthorized",
            '403'=>"forbidden",
            '404'=>"Not Found",
            '405'=>"Method Not Allowed",
            '500'=>"Internal Server Error",
            '503'=>"Service Unavailable",
            '406'=>"Not Acceptable",
            '408'=>"Request Timeout",
            '411'=>"Length Required",
            '413'=>"Payload Too Large",
            '406'=>"Not Acceptable"
    ];
    return isset($http[$code]) ? $http[$code] : "Unknown Status";
}

/**
* This takes care of requests coming from client including validation(s) as described by you.
* It takes an optional array with the following expected properties:
* 
* @param array $param; e.g:
*                       'name' => The actual name of the expected GET or POST input/request
*                       'required' => Whether the expected GET or POST input must be present. If not present a redirect or error is thrown
*                       'method' => The expected method that the expected input must follow
*                       'message' => Error message to send to client if condition(s) are not met
*                       'nullable' => If an expected input isn't set or is empty it tells us whether to proceed with request or throw error to client
*
* @return string|array|null|object
*/
function request($msg=null)
{
    return empty($msg) ? (new Request()): (new Zam())->request($msg);
}

function response($msg, $status_code=200, $session_key=null)
{
    return (new Zam())->response($msg, $status_code, $session_key);
}

function dd($message)
{
    var_dump($message);
    die;
}

function filterStringPolyfill($string)
{
    $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
    return strip_tags($str);
}

// This function formats and sanitize all parameters retrieved from user_input
function sanitize($var=null)
{
    if(empty($var))
    {
        return null;
    }

    if(!is_array($var))
    {
        $trim = trim($var);
        $fil = filterStringPolyfill($trim);
        $add = $fil;
    }
    else
    {
        $add = [];
        
        for($i=0;$i<count($var);$i++)
        {
            $trim = trim($var[$i]);
            $fil = filterStringPolyfill($trim);
            
            if(empty($fil))
            {
                continue;
            }
            
            $add []= $fil;
        }
    }
    
    return $add;
}

function env($key)
{
	try
	{
	    $dotenv = Dotenv::createImmutable(rootDir());
	    $dotenv->load();
	    return isset($_ENV[$key]) ? $_ENV[$key] : null;
	}
	catch(\InvalidPathException $e)
	{
		return null;
	}
}

function rootDir()
{
    $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
    return dirname($reflection->getFileName(), 3);
}

function error($key)
{
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(isset($_SESSION['zam_'.$key]))
    {
        $msg = $_SESSION['zam_'.$key];

        unset($_SESSION['zam_'.$key]);

        echo $msg;
    }
}
?>