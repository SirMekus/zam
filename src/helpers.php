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

function response($msg, $status_code=200)
{
    return (new Zam())->response($msg, $status_code);
}

function dd($message)
{
    var_dump($message);
    die;
}
?>