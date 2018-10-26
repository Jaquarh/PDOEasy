# PDOEasy
Safely, and easily, allows you to create a PDO Skeleton.

Copyright 2018 Kyle Jeynes @ Daniel Pickering, All Rights Reserved.

# Please Note

By default, [PDO::ATTR_EMULATE_PREPARES](http://php.net/manual/en/pdo.setattribute.php) is set to true which may cause vulnerabilities in your web application. Open the DriverContainer.php file and search for `PDO::ATTR_EMULATE_PREPARES`. Set it to false if not needed.

Continue to read on documentation:

# Configuring your PDO Skeleton (easy)
```php
/* Require the compulsory files */
require_once 'DriverContainer.php';
require_once 'DriverContainerOptions.php';

/* Use the namespace */
use \PDOEasy;

/* Instance \PDOEasy\DriverContainerOptions */
$options = new DriverContainerOptions(
  'username',
  'password',
  'database_name'
  'localhost'
);
```

# Configuring your PDO Skeleton (advanced)
```php
/* Require the compulsory files */
require_once 'DriverContainer.php';
require_once 'DriverContainerOptions.php';

/* Use the namespace */
use \PDOEasy;

/* Instance \PDOEasy\DriverContainerOptions */
$options = new DriverContainerOptions(
  'username',
  'password',
  'database_name',
  'localhost',
  'utf8mb4',
  '3306',
  'mysql'
);
```

# Creating your first Model (easy)
```php
final class MyFirstModel extends DriverContainer
{
  public function doesExist($myValue) {
    return (int) $this->query('SELECT 1 FROM myTable WHERE myRow = ?', [$myValue])->fetchColumn();
  }
}
```

# Firing up your Model (easy)
```php
DriverContainer::setOptions(new DriverContainerOptions(
  'username',
  'password',
  'database_name'
  'localhost'
));

echo MyFirstModel::getInstance()->doesExist('some context') ? "It exists" : "It doesn't exist";

DriverContainer::flush(); // close connections to all Models instanced through getInstance()
```

# Types of functions you can access (easy)
`\PDOEasy\DriverContainer::getSingleRow($sql, $values = [])` - Returns a single row of results from the query.
`\PDOEasy\DriverContainer::getMultipleRows($sql, $values = [])` - Returns a multiple row of results from the query.
`\PDOEasy\DriverContainer::query($sql, $values = [])` - Directly recieve the \PDOStatement after \PDOStatement::execute([]).

`\PDOEasy\DriverContainer::doesExist($sql, $values = [])` - Check that rows do exists.
`\PDOEasy\DriverContainer::getEntity()` - Advanced use only, returns the \PDO API instance.

# Example of a model in action

```php
final class MyFirstModel extends DriverContainer
{
  private $user;

  public function getUserById($unid) {
    $this->user = new MyUser( $this->getSingleRow('SELECT unid, name, email FROM myUserTable WHERE unid = ?', [(int) $unid]) );
  }
  
  public function getUser() { return $this->user; }
  
  public function removeUser($user) {
    if(!$user instanceof MyUser)
      throw new Exception("Call to MyFirstModel::insertUser - param expected to be of type MyUser");
      
    $this->query('DELETE FROM myUserTable WHERE unid = ? AND email = ?)', [(int) $user->getUniqueNumberId(), $user->getEmail()]);
  }
}

DriverContainer::setOptions(new DriverContainerOptions(
  'username',
  'password',
  'database_name'
  'localhost'
));

MyFirstModel::getInstance()->getUserById  ('1');
MyFirstModel::getInstance()->getUserById    (2);
MyFirstModel::getInstance()->removeUser(MyFirstModel::getInstance()->getUser());

DriverContainer::flush();
```
