<?php

/**
 * PDOEasy - MySQL made safe and easy.
 *
 * DriverContainer is an abstract model. It contains the conception of your custom models allowing you to freely, and safely, work with SQL data.
 *
 * PHP version 7
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   PDO
 * @package    PDOEasy
 * @author     Kyle Jeynes <okaydots@gmail>
 * @author     Daniel Pickering <>
 * @copyright  2018 Kyle Jeynes & Daniel Pickering
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    1.2.0
 * @release	   1.2.0
 * @link       https://github.com/Jaquarh/PDOEasy
 * @see        PDO, PDO::Prepare
 */
 
namespace PDOEasy;

abstract class DriverContainer {

    private static  $driver 	= [];
    private         $entity 	= null;
	private static	$options 	= null;
	
 /**
  * Build the configuration for the PDO API.
  * Build the configuration for the PDO API by passing in a reference to the DriverContainerOptions class.
  * @param PDOEasy\DriverContainerOptions $options contains reference to credentials.
  * @throws \Exception
  */
  
  public static function setOptions($options) {
	  if(!$options instanceof \PDOEasy\DriverContainerOptions)
		  throw new Exception("PDOEasy cannot set the options for PDO API using " . get_class($options));
	  self::$options = $options;
  }
  
 /**
  * Creates an instance of the PDO API.
  * Creates an instance of the PDO API using the refered \PDO\DriverContainerOptions credentials.
  * @return \PDO
  * @throws \Exception
  */

    private static function init() {
		if(empty(self::$options))
			throw new Exception("PDOEasy cannot set the options for PDO API without call to \PDOEasy::setOptions(\PDOEasy\DriverContainerOptions)");
		
		try {
			$pdo = new PDO($options->getDns(), $options->getUsername(), $options->getPassword(), [
				\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
				\PDO::ATTR_EMULATE_PREPARES   => false,
			]);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			return $pdo;
		} catch (\PDOException $e) {
			throw new Exception($e->getMessage());
		}
    }
	
 /**
  * Disallows cloning a model.
  * Disallows cloning a model to deny multiple connections being opened.
  */

    private function __clone() {}
	
 /**
  * Loops through each model closing the connection.
  * Loops through each model running \PDOEasy\DriverContainer::closeConnection.
  */
	
	public static function flush() {
		foreach(self::$driver as $driver) {
			$driver->closeConnection();
		}
	}
	
 /**
  * Destorys the PDO API.
  * Sets the \PDOEasy\DriverContainer::$entity to null, thus destroying the PDO API.
  * @param PDOEasy\DriverContainerOptions $options contains reference to credentials.
  */
	
	private function closeConnection() {
		$this->entity = null;
	}
	
 /**
  * Creates or returns an instance of a model.
  * Loops through \PDOEasy\DriverContainer::$driver to return an instance of a model. If no instance exists, it creates, stores and returns one.
  * @return \PDOEasy\DriverContainer parent
  */

    public static function getInstance() {
        if(self::$driver) {
			foreach(self::$driver as $driver) {
				if(get_class($driver) == get_called_class()) {
					return $driver;
				}
			}
		}
		
        $driver = get_called_class();
		$instance = new $driver;
		$instance->setConnection(self::init());
		self::$driver[] = $instance;
        return self::$driver[count(self::$driver)-1];
    }
	
 /**
  * Sets the returning PDO API.
  * Binds the \PDOEasy\DriverContainer::$entity to the declared PDO API instance.
  */
	
	public function setConnection($entity) {
		$this->entity = $entity;
	}
	
 /**
  * Returns a set of results.
  * Returns \PDOStatement::fetch() result.
  * @param string $sql Contains the SQL and binding parameters.
  * @param array $values Contains the values to bind to the SQL.
  * @return mixed
  */
	
	protected function getSingleRow		($sql, $values = []) { return $this->query($sql, $values)	->fetch(); 	}
	
 /**
  * Returns a multiple set of results.
  * Returns \PDOStatement::fetchAll() result.
  * @param string $sql Contains the SQL and binding parameters.
  * @param array $values Contains the values to bind to the SQL.
  * @return mixed
  */
	
	protected function getMultipleRows	($sql, $values = []) { return $this->query($sql, $values)	->fetchAll(); 	}
	
 /**
  * Check that a row exists when filtering the database.
  * @param string $sql Contains the SQL and binding parameters.
  * @param array $values Contains the values to bind to the SQL.
  * @return mixed
  */
  
  protected function doesExist($sql, $values = []) { return (int) $this->query($sql, [$values])->fetchColumn(); }
	
 /**
  * Directly access the \PDOStatement.
  * Returns stmt \PDO::execute() result.
  * @param string $sql Contains the SQL and binding parameters.
  * @param array $values Contains the values to bind to the SQL.
  * @return mixed
  */

    protected function query($sql, $values = []) {
		$stmt = $this->entity->Prepare($sql);
		$stmt->execute($values);
		return $stmt;
    }
	
 /**
  * Returns the direct PDO API.
  * Only to be accessed if you know what you are doing.
  * @return \PDO
  */
	
	protected function getEntity() { return $this->entity; }
}
