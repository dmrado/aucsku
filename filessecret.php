<?php
session_start();
//подключаемся к базе данных если выражение isset вернет true
include_once('func.php');
$conn=mysqli_connect($server,$login,$password);
mysqli_query($conn, "SET NAMES UTF8");
mysqli_select_db($conn, $database);
$role=$_SESSION['role'];
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
else if($role != 1 || $role != 2) {
    session_destroy();
    Header("Location: milestone.php");
}
if (!empty($_GET['file'])) {
    $filename = basename($_GET['file']);
    $filepath = 'filessecret/' . $filename;
    if (!empty($filename) && file_exists($filepath)) {
        //определяем заголовки
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        //читаем файл
        readfile($filepath);
        exit;
    } else echo '<!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8"/>
                <title>Secret files</title>
                <link href="css/form_login.css" type="text/css" rel="stylesheet"/>
            </head>
            <body>
                <form action="filessecret.php" method="get"/>';
    echo '<div id="form">';
    echo 'Вы вошли в закрытое хранилице файлов, ' . $userlogin . ', выберите файл для скачивания';
    echo '<div class="row">
                        <br/>
                       
                        <p><a href="filessecret.php?file=TTL.xlsb">СВОДНЫЙ ТЕНДЕРС С ЦЕНАМИ</a></p><br/>
                        <!--<p><a href="filessecret.php?file=TTL.xlsb">ФАЙЛ</a></p><br/>
                        <p><a href="filessecret.php?file=TTL.xlsb>ФАЙЛ</a></p><br/> 
                        <p><a href="filessecret.php?file=TTL.xlsb">ФАЙЛ</a></p><br/>
                        <p><a href="filessecret.php?file=TTL.xlsb">ФАЙЛ</a></p><br/>-->
                        
                         <p><a href="milestone.php"/>Вернуться</a></p></form>
                         
                    </div></div></body></html>';

} echo 'Что-то пошло не так. Попробуйте минут через 15.';
?>