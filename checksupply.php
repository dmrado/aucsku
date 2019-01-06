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
    echo '<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8"/>
            <title>Check supply form</title>
            <link href="css/form_login.css" type="text/css" rel="stylesheet"/>
        </head>
        <body>
            <div id="form" class="row">
                <h1>Проверьте поставку по графику</h1>
                <div class="row">
                    <!--<<p>Номер счета</p>
                    <label for="numinv"><input type="text" name="numinv"value="Введите номер счета"/></label><br />-->
                    <form action="auc.inv.php" method="GET">
                    <label for="numauc"><p>Номер аукциона</p>
                    <input type="text" name="numauc" placeholder="Введите номер аукциона" maxlength="19"/></label><br/>

                    <p>skucode продукта</p>
                    <label for="skucode"><input type="text" name="skucode" placeholder="skucode продукта"/></label><br/>

                    <p><label><input type="submit" name="ok" value="Вперед"></label></p></form><br />
                    <br/>
                    <p><a href="milestone.php"/>Вернуться</a></p>
                 </form>
           
            </div></div>;';}
?>