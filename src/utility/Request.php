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

namespace Sirmekus\App;

class Request
{

    public function __set($name, $value)
    {
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $_GET)) {
            return sanitize($_GET[$name]);
        } 
        elseif (array_key_exists($name, $_POST)) {
            return sanitize($_POST[$name]);
        }
        elseif (is_array($this->getPut()) and array_key_exists($name, $this->getPut())) {
            return sanitize($this->getPut()[$name]);
        }
        elseif (is_array($this->getRequestPayload()) and array_key_exists($name, $this->getRequestPayload())) {
            return !empty($this->getRequestPayload()[$name]) ? sanitize($this->getRequestPayload()[$name]) : null;
        } 
        else {
            return null;
        }
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPut()
    {
        $GLOBALS["_PUT"] = null;
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') 
        {
            $form_data = json_encode(file_get_contents("php://input"));
            $key_size = 52;
            $key = substr($form_data, 1, $key_size);
            $acc_params = explode($key, $form_data);
            array_shift($acc_params);
            array_pop($acc_params);
            foreach ($acc_params as $item) 
            {
                $start_key = ' name=\"';
                $end_key = '\"\r\n\r\n';
                $start_key_pos = strpos($item, $start_key) + strlen($start_key);
                $end_key_pos = strpos($item, $end_key);

                $key = substr($item, $start_key_pos, ($end_key_pos - $start_key_pos));

                $end_value = '\r\n';
                $value = substr($item, $end_key_pos + strlen($end_key), -strlen($end_value));
                $_PUT[$key] = $value;
            }
            if(isset($_PUT)){
                $GLOBALS["_PUT"] = $_PUT;
            }
            
        }
        return $GLOBALS["_PUT"];
    }

    public function put($param)
    {
        return isset($this->getPut()[$param]) ? $this->getPut()[$param] : null;
    }

    //This is set when the request is sent in form of JSON
    public function getRequestPayload()
    {
        return json_decode(file_get_contents("php://input"), true);
    }
}