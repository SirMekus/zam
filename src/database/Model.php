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

namespace Sirmekus\Database;

use Sirmekus\Database\MYSQL;

/**
 * Database Class that extends and add feature to the MYSQL class
 *
 * @author   SirMekus <mekus600@gmail.com>
 * @author-profile https://www.facebook.com/emeka.ohakwe/
 *
 * @license MIT
 *
 */

/**
 * PHP class
 * Optionally pass in database auth credentials as argument
 *
 */

class Model extends MYSQL {

    public $database;

    public $table;

    private $data = [];

    public function __construct($host=null, $user=null, $database=null, $password=null)
	{
        if(!empty($host) and !empty($user) and !empty($database) and !empty($password))
        {
            $host = $host;
            $user = $user;
            $database = $database;
            $password = $password;
        }
        else
        {
            $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
            $vendorDir = dirname($reflection->getFileName(), 3);
            
            include $vendorDir.'/env.php';
            
            $host = DB_HOST;
            $user = DB_USER;
            $database = DB_NAME;
            $password = DB_PASS;
        }

		parent::__construct($host, $user, $password);
        
        $this->database = $database;

        $this->SetNames( 'utf8mb4' );
	}

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
	
	public function __isset($name)
    {
        return isset($this->data[$name]);
    }
	
	public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function getTable($table=null)
    {
        if(!empty($table))
        {
            return $table;
        }
        elseif(!empty($this->table))
        {
            return $this->table;
        }
        else
        {
            return snakeCase((new \ReflectionClass($this))->getShortName());
        }
    }

    public function select($configuration=null, $where=null, $table=null)
    {
        $param = [ 'table' => $this->getTable($table), 'db_name'=>$this->database];

        if(!empty($where))
        {
            $whereClause = $this->convertToWhereClause($where);

            $param['wheres'] = [$whereClause];
        }

        if(!empty($configuration) and isset($configuration['column']))
        {
            $param['params'] = [$configuration['column']];
        }

        if(!empty($configuration) and isset($configuration['orderBy']))
        {
            $param['order_by'] = $configuration['orderBy'];
        }

        if(!empty($configuration) and isset($configuration['groupBy']))
        {
            $param['group_by'] = $configuration['groupBy'];
        }

        if(!empty($configuration) and isset($configuration['debug']))
        {
            $param['debug'] = 'on';
        }

        if(!empty($configuration) and isset($configuration['limit']))
        {
            $param['limit'] = $configuration['limit'];
        }

        if(!empty($configuration) and isset($configuration['offset']))
        {
            $param['offset'] = $configuration['offset'];
        }

        $user_data = $this->find( $param );

        return $user_data;
    }

    public function insert($data, $table=null)
    {
        return $this->create( $this->getTable($table), $this->database, $data );
    }

    public function update($data, $where=null, $table=null)
    {
        $arg = [ 'table' => $this->getTable($table), 'db_name'=>$this->database, 'columns' => $data ];

        if(!empty($where))
        {
            $whereClause = $this->convertToWhereClause($where);

            $arg['wheres'] = [$whereClause];
        }

        return $this->UpdateTable( $arg );
    }

    /**
     * Inserts or updates record in database. This method checks if a record exists in the database. If it does then it updates it with the 
     * second argument else it merges the first and second arguments (arrays) and inserts/creates the record instead. The first two arguments 
     * should be passed as multi-dimensional array (key-value pair). E.g, ['email'=>"mekus600@gmail.com"]. You can add as many items in the 
     * multi-dimensional array
     *
     * @param array $params; e.g:
     *                       'columnIdentifier' => a unique ID that should uniquely identify record in the database.
     *                       'data' => Data to be inserted if record is found (else it'll be merged and a new record will be created)
     *                       'table' => the database to search from or insert into.
     *
     * @return bool
     */
    public function updateOrCreate($columnIdentifier, $data, $table=null)
    {
        $table = $this->getTable($table);

        $user_data = $this->select(null, $columnIdentifier, $table);

        if(empty($user_data))
        {
            $this->insert(array_merge($data, $columnIdentifier), $table);
        }
        else
        {
            $this->update($data, $columnIdentifier, $table);
        }

        return true;
    }

    public function convertToWhereClause($columnIdentifier)
    {
        $whereClause = "";

        $lastKey = array_key_last($columnIdentifier);

        foreach($columnIdentifier as $key=>$value){

            $whereClause .= !empty($whereClause) ? " ".$key."="."'".$value."'" : $key."="."'".$value."'";

            if($key !== $lastKey){
                $whereClause .= " and ";
            }
        }

        return $whereClause;
    }

    public function save($table=null)
    {
        return $this->insert($this->data, $this->getTable($table));
    }

    public function delete($where=null, $table=null)
    {
        $param = [ 'table' => $this->getTable($table), 'db_name'=>$this->database];

        if(!empty($where))
        {
            $whereClause = $this->convertToWhereClause($where);

            $param['wheres'] = [$whereClause];
        }

        else
        {
            if(!empty($this->data))
            {
                $whereClause = $this->convertToWhereClause($this->data);

                $param['wheres'] = [$whereClause];
            }
        }

        return $this->DeleteRecord($param);
    }
}
?>