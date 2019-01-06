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
        if (isset($_POST['numauc']) /*&& is_numeric($_POST['numauc']) */&& isset($_POST['skucode']) /*&& is_numeric($_POST['skucode']) */&& isset($_POST['shedule_num']) && isset($_POST['date1']) && isset($_POST['shedule_qty'])) {
            $numauc = mysqli_real_escape_string($conn, $_POST['numauc']);
            $skucode = mysqli_real_escape_string($conn, $_POST['skucode']);

            $shedule_num = mysqli_real_escape_string($conn, $_POST['shedule_num']);
            $date1 = mysqli_real_escape_string($conn, $_POST['date1']);
            $date2 = mysqli_real_escape_string($conn, $_POST['date2']);
            $date3 = mysqli_real_escape_string($conn, $_POST['date3']);
            $date4 = mysqli_real_escape_string($conn, $_POST['date4']);
            $date5 = mysqli_real_escape_string($conn, $_POST['date5']);
            $date6 = mysqli_real_escape_string($conn, $_POST['date6']);
            $date7 = mysqli_real_escape_string($conn, $_POST['date7']);
            $date8 = mysqli_real_escape_string($conn, $_POST['date8']);
            $date9 = mysqli_real_escape_string($conn, $_POST['date9']);
            $date10 = mysqli_real_escape_string($conn, $_POST['date10']);
            $date11 = mysqli_real_escape_string($conn, $_POST['date11']);
            $date12 = mysqli_real_escape_string($conn, $_POST['date12']);

           // $shedule_date = strtotime($date); // переводит из строки в дату
            //$shedule_date1 = date("Y-m-d", $shedule_date);//переводит в правильный формат

            $shedule_num1 = explode(" ", $shedule_num);
            $shedule_date1 = array($date1, $date2, $date3, $date4, $date5, $date6, $date7, $date8, $date9, $date10, $date11, $date12);

            $array_date = array_combine($shedule_num1, $shedule_date1);


            foreach ($array_date as $shedule_num1 => $shedule_date1){
                echo "<pre>";
                print_r($array_date);
                echo "</pre>";
            }

            $shedule_qty = mysqli_real_escape_string($conn, $_POST['shedule_qty']);

            $shedule_qty1 = explode(" ", $shedule_qty);

            $array_sku = array_combine($shedule_date1, $shedule_qty1);//правильно сливает

            echo "<pre>";
             print_r($array_sku);
             echo "</pre>";

            foreach ($array_sku as $shedule_date1 => $shedule_qty1) {


            $query6 = mysqli_query($conn, "SELECT * FROM auction WHERE numauc='" . $numauc . "' && skucode='" . $skucode . "'");
            $count6 = mysqli_num_rows($query6);
            if ($count6 > 0) {
                $result6 = mysqli_query($conn, "INSERT INTO `shedule` (`shedule_id`, `numauc`, `skucode`, `shedule_date`, `shedule_qty`, `remarkshedule`, `shedule_num`, `shedulefield2`, `shedulefield3`) VALUES (NULL,'" . $numauc . "', '" . $skucode . "', '" . $shedule_date1 . "', '" . $shedule_qty1 ."', '', '". $shedule_num1 ."', '', '')");
            }

                if ($result6) {
                    echo 'График поставки по аукциону ' .$numauc. ' успешно внесен. <br/>';

                } else echo 'График поставки по аукциону  .$numauc. не внесен, повторите!';
                exit();
            } echo 'Такого аукциона нет в базе. Проверьте правильность ввода.';
            exit();
        } echo '1';
    } echo '2';
?>
