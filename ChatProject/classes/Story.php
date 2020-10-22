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
   $dsn = 'mysql:host='.self::$host.';dbname='.self::$db.';charset='.self::$charset;
   $this->$pdo = new PDO($dsn, self::$user, self::$pass, self::$opt);
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
self::$messUserArray[] = $mess."-".$name;
}

// парсинг массива
private function separation(){
$array = [];
foreach (self::$messUserArray as $value) {
preg_match('/(.*)-(.*)/is', $value, $m);
$array[] = $m;
}
return $array;
}
//запись в базу данных

public function dateBaseRec(){
$array = $this->separation();
static $query;
if($query == ""){
$query = $this->$pdo->prepare("INSERT INTO chatmessages (name, message) VALUES (?, ?)");
}
foreach($array as list(,$message,$name)){
echo $message;
$query->execute([$name, $message]);
}
self::$messUserArray = [];
}
//закрытие чата

public function disconnected(){
  $array = $this->separation();
  static $query;
  if($query == ""){
  $query = $this->$pdo->prepare("INSERT INTO chatmessages (name, message) VALUES (?, ?)");
  }
  var_dump($array);
  foreach($array as list(,$message,$name)){
  $query->execute([$name, $message]);
  }
  self::$messUserArray = [];
}

//запись в DOM
private function addDivs($message,$class = null){
  $divMessage = "<div class = \"". $class. "\">".$message."</div>";
  return $divMessage;
}
public function previousMessages(){
  $messageArray = [];
  $stm = $this->$pdo->prepare("SELECT name, message FROM chatmessages");
  $stm->execute();
  while($result = $stm->fetch(PDO::FETCH_ASSOC)){
    echo $this->addDivs($result['name'],"user").'сказал'.$this->addDivs($result['message'],"message");
  };
}

private function __clone () {}
private function __wakeup () {}
}
