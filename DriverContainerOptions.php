<?php

/**
 * PDOEasy - MySQL made safe and easy.
 *
 * DriverContainerOptions is a configuration class. It contains the configuration of your custom models allowing you to freely, and safely, work with SQL data.
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
 * @release       1.2.0
 * @link       GIT LINK HERE
 * @see        PDO_MYSQL, PDO_ODBC, PDO_PGSQL, PDO_SQLITE
 */

namespace PDOEasy;

final class DriverContainerOptions
{
    
    private $username;
    private $password;
    private $hostAddress;
    private $charset;
    private $port;
    private $driver;
    private $databaseName;
    
    /**
     * Builds the instance.
     * Sets the parameters of the class and returns an object of the class.
     * @param string $databaseUsername Contains the database username.
     * @param string $databasePassword Contains the database password.
     * @param string $databaseName Contains the database name.
     * @param string $databaseHostAddress Contains the database host.
     * @param string $databaseCharset Contains the database charset.
     * @param string $databasePort Contains the \PDO API connection port.
     * @param string $driver Contains the \PDO API connection driver.
     * @return \PDOEasy\DriverContainerOptions
     */
    
    public function __construct($databaseUsername, $databasePassword, $databaseName, $databaseHostAddress = "localhost", $databaseCharset = "utf8mb4", $databasePort = 3306, $driver = "mysql")
    {
        $this->username     = $databaseUsername;
        $this->password     = $databasePassword;
        $this->hostAddress  = $databaseHostAddress;
        $this->databaseName = $databaseName;
        $this->charset      = $databaseCharset;
        $this->port         = (int) $databasePort;
        switch (strtolower($driver)) {
            case 'mysql':
                $this->driver = $driver;
                break;
            default:
                throw new Exception('Call to \PDOEasy\DriverContainerOptions::__construct - unknown driver parameter.');
        }
    }
    
    /**
     * Returns a constructed PDO_DSN
     * Returns a string constructed PDO_DSN using the given configuration.
     * @return string
     */
    
    public function getDns()
    {
        return "{$this->driver}:host={$this->hostAddress};dbname={$this->databaseName};port={$this->port};charset={$this->charset}";
    }
    
    /**
     * Returns the database username.
     * @return string
     */
    
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Returns the database password.
     * @return string
     */
    
    public function getPassword()
    {
        return $this->password;
    }
    
}
