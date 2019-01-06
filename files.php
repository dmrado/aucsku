<?php
session_start();
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

        if (!empty($_GET['file'])){
        $filename = basename($_GET['file']);
        $filepath = 'files/'.$filename;
        if (!empty($filename) && file_exists($filepath)){
            //определяем заголовки
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/zip" );
            header("Content-Transfer-Encoding: binary");

            //читаем файл
            readfile($filepath);
            exit;
        }else echo 'Что-то пошло не так. Попробуйте чуть позже.';
    }
        else echo
        $form1 =   '<!DOCTYPE html>
                    <html>
                        <head>
                            <meta charset="utf-8"/>
                            <title>File store</title>
                            <link href="css/form_login.css" type="text/css" rel="stylesheet"/>
                        </head>
                        <body>
                        <form action="inputinv.php" method="get"/>';
                    echo '<div id="form">';
                    echo 'Вы вошли в хранилице файлов, ' . $userlogin . ', выберите файл для скачивания';
                    echo '<div class="row">
                        <br/>
                       <!-- <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation"></nav>-->
                        <p><a href="files.php?file=TENDERS TOTAL LAST.xlsx">СВОДНЫЙ ТЕНДЕРС</a></p><br/>
                        <p><a href="files.php?file=PRAJ REWIEV LAST.xlsb">РЕВЬЮ ПРАДЖИСАН</a></p><br/>
                        <p><a href="files.php?file=TTL.xlsb">АНАЛИТИКА</a></p><br/>
                        <!-- <p><a href="#" class="ref">ФАЙЛ</a></p><br/> 
                        <p><a href="#" class="ref">ФАЙЛ</a></p><br/>
                        <p><a href="#" class="ref">ФАЙЛ</a></p><br/>-->
                        
                         <p><a href="milestone.php"/>Вернуться</a></p></form>
                    </div></div></body></html>';




    /*$ret = ftp_nb_get($my_connection, "test", "users 2.numbers", FTP_BINARY);
    while ($ret == FTP_MOREDATA) {
        echo "Скачиваем файл";

        $ret = ftp_nb_continue($my_connection);

    }
    if ($ret != FTP_FINISHED){
        echo "При скачивании произошла ошибка";
        (exit);
    } else "Скачивание успешно завершено";*/
    ?>