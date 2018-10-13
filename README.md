# PDOEasy
Safely, and easily, allows you to create a PDO Skeleton. Continue to read on documentation:

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

# Example of a model

```php
final class MyFirstModel extends DriverContainer
{
  public function getUserById($unid) {
    return new MyUser( $this->getSingleRow('SELECT unid, name, email FROM myUserTable WHERE unid = ?', [(int) $unid]) );
  }
  
  public function insertUser($user) {
    if(!$user instanceof MyUser)
      throw new Exception("Call to MyFirstModel::insertUser - param expected to be of type MyUser");
      
    $this->query('INSERT INTO myUserTable (name, email) VALUES (?, ?)', [(int) $user->getUniqueNumberId(), $user->getEmail()]);
  }
}
```
