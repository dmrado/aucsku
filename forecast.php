<?php
session_start();
//подключаемся к базе данных если выражение isset вернет true
include_once('func.php');
$conn=mysqli_connect($server,$login,$password);
mysqli_query($conn, "SET NAMES UTF8");
mysqli_select_db($conn, $database);
if (isset($_POST['userlogin']) && isset($_POST['userpassword'])) {
    $userlogin = mysqli_real_escape_string($conn, $_POST['userlogin']);
    $userpassword = md5(mysqli_real_escape_string($conn, $_POST['userpassword']));
}
else if (isset($_SESSION['userlogin']) && isset($_SESSION['userpassword'])) {
    $userlogin = mysqli_real_escape_string($conn, $_SESSION['userlogin']);
    $userpassword = mysqli_real_escape_string($conn, $_SESSION['userpassword']);
}
else {
    Header("Location: index.html");
}
$result=mysqli_query($conn, "SELECT * FROM users WHERE userlogin='".$userlogin."' && userpassword='".$userpassword."'");
$count=mysqli_num_rows($result);
if($count==0) {
    session_destroy();
    Header("Location: index.html");
}
else {
    if (isset ($_GET['skucode']) && isset ($_GET['month'])){
        $skucode=mysqli_real_escape_string($conn, $_GET['skucode']);
        $month=mysqli_real_escape_string($conn, $_GET['month']);

        $result=mysqli_query($conn, "SELECT * FROM shedule WHERE skucode='".$skucode."' month='".$month."'");//проверяем значение переменной монтс и пишем отсюда
    }
//начинаем вывод на экран
    echo'<!DOCTYPE html>
            <head>
                <meta charset="utf-8"/>
                <title>Invoice input</title>
                <link href="css/form_login.css" type="text/css" rel="stylesheet"/>
            </head>
            <body>
                <div id="form">
                    <h1>Форма запроса форкаста</h1>
                    <form action="forecast.php" method="get"/>
                    
                    <p><label for="skucode">skucode
                    <input type="text" id="text" name="skucode" value="" placeholder="skucode"/>
                   
                    <label for="month">Месяц
                    <input type="text" id="text" name="month" value="" placeholder="month"/>
                    
                    <input type="submit" name="submit" value="Отправить"/>
                </div>
            </body>
            ';

}

?>
