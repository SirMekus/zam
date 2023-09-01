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
@copyright SirMekus 2022                                                   !
                                                                    ¦
************************************************************************/

namespace Sirmekus\App;

use Sirmekus\App\Request;
/**
 * Utility Class that takes care of random stuff (can be extended) like form input, generating random numbers, etc..
 *
 * @author   SirMekus <mekus600@gmail.com>
 * @author-profile https://www.facebook.com/emeka.ohakwe/
 *
 * @license MIT
 *
 */
/**
 * PHP class
 * Doesn't need any argument to get started
 *
 */

class Zam extends Request
{
	//AJAX function Checker to be run if a page was accessed via an ajax call
	//if AJAX we echo the passed parameter else we just return control to the controller to do whatever
	public function response($msg, $status_code=200, $session_key=null)
	{
		$ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

		header("HTTP/1.1 $status_code. ".getHttpMesssage($status_code));
        
		if ($ajax)
		{
			print_r(json_encode([
				'message' => $msg
			], JSON_FORCE_OBJECT));
	    }
	    else
	    {
			if(session_status() !== PHP_SESSION_ACTIVE) session_start();

			$error = $msg["message"] ?? $msg;

			if(empty($session_key))
			{
				$_SESSION["status"] = $error;
			}
			else
			{
				$_SESSION["zam_".$session_key] = $error;
			}

			if(isset($_SERVER["HTTP_REFERER"]))
			{
				header("Location: ".$_SERVER["HTTP_REFERER"]."");
			}
			else
			{
				header("Location: /");
            }
	    }
		exit;
	}


	/**
     * This takes care of requests coming from client including validation(s) as described by you.
	 * It takes an array with the following expected properties:
     *
     * @param array $param; e.g:
     *                       'name' => The actual name of the expected GET, POST, etc input/request
     *                       'required' => Whether the expected input must be present. If not present a redirect or error is thrown
     *                       'method' => The expected method that the expected input must follow
	 *                       'message' => Error message to send to client if condition(s) are not met
	 *                       'nullable' => If an expected input isn't set or is empty it tells us whether to proceed with request or throw error to client
     *
     * @return string|array|null
     */
	public function request($param)
	{
		$name = $param["name"];
		
		//Whether or not we should exit the script if the specified post/get param isn't defined
		$required = $param["required"] ?? true;
		
		//Whether this is a "Get", "Put" or "Post" form
		$method = $param["method"] ?? "post";
		
		//The message to echo to the user if the specified form field isn't set or is empty
		$invalid = $param["message"] ?? "Please enter a valid value";
		
		//In a situation whereby the key isn't set, it tells us whether to proceed (and return a null value) or exit it.
		$nullable = $param["nullable"] ?? false;

		$errorPayload = [
			'message'=>$invalid, 'target'=>$name
		];

		if(strtoupper($this->method()) != strtoupper($method))
		{
			$this->response("Unsupported method", 405, $name);
		}

		$value = sanitize($this->$name);
		
		if($required)
		{
			if(empty($value))
			{
				if(!$nullable)
				{
					$this->response($errorPayload, 422, $name);
				}
			}
		}
		return $value;
	}
}
?>