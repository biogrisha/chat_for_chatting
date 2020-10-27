<?php
class Story{
//Singleton
private static $instance = null;
// messages
private static $messUserArray = [];
// connfig
private static $db = 'chat';
private static $host = 'localhost';
private static $user = 'root';
private static $pass = 'root';
private static $charset = 'utf8';
private static $opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
// obj
private $pdo;

//конструктор
 private function __construct(){
   $this->pdo = new PDO("mysql:host=".self::$host, self::$user, self::$pass, self::$opt);
   $this->pdo->exec('CREATE DATABASE IF NOT EXISTS '.self::$db);
   $this->pdo->exec('use '.self::$db);
   $this->pdo->exec('CREATE TABLE IF NOT EXISTS chatmessages(
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR (20) NOT NULL,
  message TEXT,
  PRIMARY KEY (id)
)');
}

//создание объекта
static function alive()
{
  if (static::$instance === null) {
             static::$instance = 1;
         }
         return new self();
}

//запись в массив
public function arrayRec($name, $mess){
self::$messUserArray[] = $mess;
self::$messUserArray[] = $name;
}

// парсинг массива
//запись в базу данных

public function dateBaseRec(){
static $query;
if($query == ""){
$query = $this->pdo->prepare("INSERT INTO chatmessages (name, message) VALUES (?, ?)");
}
echo self::$messUserArray[0];
$query->execute([self::$messUserArray[1], self::$messUserArray[0]]);
self::$messUserArray = [];
}
//запись в DOM
private function addDivs($message,$class = null){
  $divMessage = "<div class = \"". $class. "\">".$message."</div>";
  return $divMessage;
}
public function previousMessages(){
  $messageArray = [];
  $stm = $this->pdo->prepare("SELECT name, message FROM chatmessages");
  $stm->execute();
  while($result = $stm->fetch(PDO::FETCH_ASSOC)){
    echo $this->addDivs($result['name'],"user").'сказал'.$this->addDivs($result['message'],"message");
  };
}

private function __clone () {}
private function __wakeup () {}
}
