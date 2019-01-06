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
            while ($ar = mysqli_fetch_array($result)) {
            $userlogin = $ar['userlogin'];
            $userpassword = $ar['userpassword'];
            $username = $ar['username'];
            $userid = $ar['userid'];
            $email = $ar['email'];
            $role = $ar['role'];


            $_SESSION['userlogin'] = $userlogin;
            $_SESSION['userpassword'] = $userpassword;
            $_SESSION['username'] = $username;
            $_SESSION['userid'] = $userid;
            $_SESSION['email'] = $email;
            $_SESSION['role']=$role;

            }
            echo '<!DOCTYPE html>
                <html>
                    <head>
                        <meta charset="utf-8"/>
                        <title>Milestone</title>
                        <link href="css/form_login.css" type="text/css" rel="stylesheet"/>
                    </head>
                    <body>';
            echo '<div id="form">';
            echo '<h2>Вы выполнили вход, ' . $username . ', выберите действие: </h2>';
            echo '<div class="row">
                    <br/>
                   <!-- <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation"></nav>-->
                 <div class="figure">
                    <div class="thumbnail">
                        <!--<img src ="images/#"></div>-->
                    <div class="caption">
                        <p><a href="inputauc.php" class="btn btn-primary">ДОБАВИТЬ АУКЦИОН (ТЕНДЕР)</a></p><br/><!--быстрая проверка аукциона спецификация vs. счета и далее после checksupply.php пойдет auc.inv.php и конец-->
                 </div></div>
                 
                 <div class="figure">
                    <div class="thumbnail">
                        <!--<img src ="images/#"></div>-->
                    <div class="caption">
                        <p><a href="inputschedule.html" class="btn btn-primary">ВВЕСТИ ГРАФИК ПОСТАВКИ (ТЕНДЕР)</a></p><br/><!--Tender вводит график поставки из спецификации выигранного аукциона-->
                 </div></div>
                 
                 <div class="figure">
                    <div class="thumbnail">
                        <!--<img src ="images/#"></div>-->
                    <div class="caption">
                        <p><a href="checksupply.php" class="btn btn-primary">ПРОВЕРИТЬ ПОСТАВКУ, ВВЕСТИ ИНВОЙС (АНДРЕЙ)</a></p><br/><!--быстрая проверка аукциона спецификация vs. счета и далее после checksupply.php пойдет auc.inv.php и конец-->
                 </div></div>
                    
                 <div class="figure">
                    <div class="thumbnail">
                        <!--<img src ="images/#"></div>-->
                    <div class="caption">
                        <p><a href="rx.php" class="btn btn-primary"">ПРОВЕРИТЬ ОТГРУЗКУ (Rx)</a></p><br/><!--Rx проверяет отгрузку на дистриба по аукциону-->   
                 </div></div>
                 
                 <div class="figure">
                    <div class="thumbnail">
                        <!--<img src ="images/#"></div>-->
                    <div class="caption">
                        <p><a href="files.php" class="btn btn-primary">ФАЙЛЫ</a></p><br/>
                 </div></div>
                 
                <!-- <div class="figure">
                    <div class="thumbnail">
                        <!--<img src ="images/#"></div>-->
                    <!--<div class="caption">
                        <p><a href="filessecret.php" class="btn btn-primary">АНАЛИТИКА</a></p><br/>
                 </div></div>-->
                 
                 <div class="figure">
                    <div class="thumbnail">
                        <!--<img src ="images/#"></div>-->
                    <div class="caption">
                        <p><a href="about.html" class="btn btn-primary">О ПРОГРАММЕ</a></p><br/>
                 </div></div>
                 
             
                    </nav>
                </div></body></html>';
    }
?>
