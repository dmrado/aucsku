<?php
require "func.php";
try {
    $db = new PDO("mysqli:host=$server;dbname=$database", $login, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("set names utf8");
}
catch(PDOException $e) {
    echo $e->getMessage();
}

//вывести ошибки
//echo $conn->errorCode();
//echo $conn->errorInfo();


//Постоянное соединение
//$dbh = new PDO('mysqli:host=$server;dbname=database', $login, $password, array(
  //  PDO::ATTR_PERSISTENT => true
//));


//Закрытие соединения
//$db = null;

?>
