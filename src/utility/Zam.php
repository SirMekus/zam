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

class Zam
{
	//AJAX function Checker to be run if a page was accessed via an ajax call
	//if AJAX we echo the passed parameter else we just return control to the controller to do whatever
	public function response($msg, $status_code=200)
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
			session_start();

			$_SESSION["status"] = $msg;

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

    function filterStringPolyfill($string)
    {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
        return strip_tags($str);
    }
	
	// This function formats and sanitize all parameters retrieved from user_input
    public function sanitize($var)
    {
		if(!is_array($var))
		{
	        $trim = trim($var);
			$fil = $this->filterStringPolyfill($trim);
	        $add = $fil;
		}
		else
		{
			$add = [];
			
			for($i=0;$i<count($var);$i++)
			{
	            $trim = trim($var[$i]);
                $fil = $this->filterStringPolyfill($trim);
	            
				if(empty($fil))
				{
					continue;
				}
                
			    $add []= $fil;
			}
		}
		
		return $add;
    }


	/**
     * This takes care of requests coming from client including validation(s) as described by you.
	 * It takes an array with the following expected properties:
     *
     * @param array $param; e.g:
     *                       'name' => The actual name of the expected GET or POST input/request
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
		
		//Whether this is a "Get" or "Post" form
		$type = $param["method"] ?? "post";
		
		//The message to echo to the user if the specified form field isn't set or is empty
		$invalid = $param["message"] ?? "Please enter a valid value";
		
		//In a situation whereby the key isn't set, it tells us whether to proceed (and return a null value) or exit it.
		$nullable = $param["nullable"] ?? false;

		$defaultStatusCode = 422;

		$errorPayload = [
			'message'=>$invalid, 'target'=>$name
		];
		
		switch($type)
		{
			case "post":
			    if(!isset($_POST[$name]))
			    {
					if($nullable == false)
					{
						$this->response($errorPayload, $defaultStatusCode);
				        exit;
					}
					else
					{
						return null;
					}
			    }
			    else
			    {
					$value = $this->sanitize($_POST[$name]);
				}
				
				break;
				
			case "get":
			    if(!isset($_GET[$name]))
			    {
					if($required != false)
					{
						$this->response($errorPayload, $defaultStatusCode);
				        exit;
					}
					else
					{
						return;
					}
			    }
			    else
			    {
					$value = $this->sanitize($_GET[$name]);
				}
				
				break;
				
			default:
			    //do nothing
		}
		
		if($required == true)
		{
			if(empty($value))
			{
				$this->response($errorPayload, $defaultStatusCode);
				exit;
			}
		}
		return $value;
	}
		
}
?>