<?php

ini_set('display_errors', 1);

define('DSN', 'mysql:dbhost=localhost;dbname=sns_php');
define('DB_USERNAME', 'dbuser01');
define('DB_PASSWORD', 'password');

define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);


session_start();

// require_once(__DIR__ . '/Exception/error.php');

function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

class Index extends Controller {

  public function run() {
    if (!$this->isLoggedIn()) {
      // login
      header('Location: ' . SITE_URL . '/login.php');
      exit;
    }

    // get users info
    $userModel = new User();
    $this->setValues('users', $userModel->findAll());
  }

}

class Controller {

  private $_errors;
  // private $_values;
  protected $_values;

  public function __construct() {
    $this->_errors = new \stdClass();
    $this->_values = new \stdClass();
  }

  protected function setValues($key, $value) {
    $this->_values->$key = $value;
  }

  public function getValues() {
    return $this->_values;
  }

  protected function setErrors($key, $error) {
    $this->_errors->$key = $error;
  }

  public function getErrors($key) {
    return isset($this->_errors->$key) ?  $this->_errors->$key : '';
  }

  protected function hasError() {
    return !empty(get_object_vars($this->_errors));
  }

}

class Sinki extends Controller {

  public function run() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->postProcess();
    }
  }

  protected function postProcess() {

    $this->setValues('email', $_POST['email']);

    if ($this->hasError()) {
      return;
    } else {
          // create user
          try {
            $userModel = new User();

            $userModel->create([
              'email' => $_POST['email'],
              'password' => $_POST['password']
            ]);
          } catch (DuplicateEmail $e) {
            $this->setErrors('email', $e->getMessage());
            return;
          }
          // redirect to login
          header('Location: ' . SITE_URL . '/login.php');
          exit;
    }
  }


}



class Model {
  protected $db;

  public function __construct() {
    try {
      $this->db = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
    } catch (PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }
}

class User extends Model {

  public function create($values) {
    $stmt = $this->db->prepare("insert into users (email, password, created, modified) values (:email, :password, now(), now())");
    $res = $stmt->execute([
      ':email' => $values['email'],
      ':password' => password_hash($values['password'], PASSWORD_DEFAULT)
    ]);
    if ($res === false) {
      throw new DuplicateEmail();
    }
  }

  public function login($values) {
    $stmt = $this->db->prepare("select * from users where email = :email");
    $stmt->execute([
      ':email' => $values['email']
    ]);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
    $user = $stmt->fetch();

    if (empty($user)) {
      throw new Exception('該当ユーザなし');
    }

    if (!password_verify($values['password'], $user->password)) {
      throw new Exception('パスワードが違う');
    }

    return $user;
  }



}

