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

require_once './vendor/autoload.php';

use Sirmekus\App\Zam;

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
    $settings = [
        'http'=>[
            '200'=>"ok",'301'=>"Moved Permanently",'308'=>"Permanent Redirect",'422'=>"Unprocessable Entity",'401'=>"unauthorized",'403'=>"forbidden",'404'=>"Not Found",'405'=>"Method Not Allowed",'500'=>"Internal Server Error",'202'=>"Accepted"
        ]
    ];

    return isset($settings['http'][$code]) ? $settings['http'][$code] : "Unknown Status";
}

/**
* This takes care of requests coming from client including validation(s) as described by you.
* It takes an array with the following expected properties:
* 
* @param array $param; e.g:
*                       'name' => The actual name of the expected GET or POST input/request
*                       'required' => Whether the expected GET or POST input must be present. If not present a redirect or error is thrown
*                       'method' => The expected method that the expected input must follow
*                       'message' => Error message to send to client if condition(s) are not met
*                       'nullable' => If an expected input isn't set or is empty it tells us whether to proceed with request or throw error to client
*
* @return string|array|null
*/
function request($msg)
{
    return (new Zam())->request($msg);
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