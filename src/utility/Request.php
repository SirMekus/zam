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

use Sirmekus\App\Zam;

class Request {
    
    public function __set($name, $value)
    {
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }
	
	public function __get($name)
    {
        $zam = new Zam();

        if (array_key_exists($name, $_GET)) {
             return $zam->sanitize($_GET[$name]);
        }
        elseif (array_key_exists($name, $_POST)) {
                 return $zam->sanitize($_POST[$name]);
        }
        else{
            return null;
        }
    }
}
?>