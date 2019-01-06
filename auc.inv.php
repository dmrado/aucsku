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
} else {
    echo '<!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8"/>
                <title>Auction result</title>
                <link href="css/form_login.css" type="text/css" rel="stylesheet"/>
            </head>
            <body>';
        if (isset($_GET['numauc']) && ($_GET['numauc']!='') && isset($_GET['skucode']) && ($_GET['skucode']!='')){
        $numauc=mysqli_real_escape_string($conn, $_GET['numauc']);
        $numauc = trim($_GET['numauc']);
        $skucode=mysqli_real_escape_string($conn, $_GET['skucode']);
        $skucode = trim($_GET['skucode']);

            $result6=mysqli_query($conn, "SELECT * FROM auction WHERE numauc='".$numauc."' && skucode='".$skucode."'");
            $count=mysqli_num_rows($result6);
            if($count>0){

        echo '<div id="form">
            <form action="inputinv.php" method="post"/>';
        //Вытащили номер аукциона и выводим по нему его данные
        $result=mysqli_query($conn, "SELECT * FROM auction WHERE numauc='".$numauc."' && skucode='".$skucode."'");
            while($row=mysqli_fetch_array($result)) {
                $dateauc = $row['dateauc'];
                $skucode = $row['skucode'];
                $remarkauc = $row['remarkauc'];
                $userid = $row['userid'];
                $lawauc = $row['lawauc'];

                //вытащили из таблицы product продукты по skucode точно по аналогии с кодом выше
                $result4 = mysqli_query($conn, "SELECT * FROM product WHERE skucode='" . $skucode . "'");
                while ($row4 = mysqli_fetch_array($result4)) {
                    $prodname = $row4['prodname'];
                    echo "<p><span> " . $prodname . "</span></p>";
                }
                $qtyauc = $row['qtyauc'];
                $priceauc = $row['priceauc'];
                $amountauc = $row['amountauc'];


                //вытащили из таблицы users сотрудника который ввел номер аукциона по userid точно по аналогии с кодом выше
                $result3 = mysqli_query($conn, "SELECT * FROM users WHERE userid='" . $userid . "'");
                while ($row3 = mysqli_fetch_array($result3)) {
                    $useremail = $row3['email'];//это емейл тендерного КАЕ не путать с сотрудником в глобальной переменной $_SESSION, тот кто залогинился
                    $username = $row3['username']; // вот тут ты забираешь еще и имя. не надо под каждую сущность делать отдельный запрос. 1 запрос в таблицу и все забираешь
                }
                echo "<p>Номер аукциона: " . $numauc . "<br/>
                    Дата: " . $dateauc . "<br/>
                    Количество в спецификации (уп.): " . $qtyauc . "<br/>
                    Цена по позиции спецификации с НДС: " . $priceauc . "<br/>
                    Сумма по позиции спецификации с НДС: " . $amountauc . "<br/>
                    Тендер-КАЕ: " . $username . "<br/>
                    Примечание: " . $remarkauc . "<br/>";
                echo "---------------------------------------------------<br/>";
                $qtyinvsum = 0;
                $amountinvsum = 0;
                $result2 = mysqli_query($conn, "SELECT * FROM invoice WHERE numauc='" . $numauc . "'");
                while ($row2 = mysqli_fetch_array($result2)) {
                    $numinv = $row2['numinv'];
                    $dateinv = $row2['dateinv'];
                    $qtyinv = $row2['qtyinv'];
                    $skucode = $row2['skucode'];
                    $qtyinvsum += $qtyinv; //суммируем все количества по счетам


                    $result5 = mysqli_query($conn, "SELECT * FROM invoiceprice WHERE numauc='" . $numauc . "' && numinv='".$numinv."'");
                    while ($row5 = mysqli_fetch_array($result5)) {
                        $priceinv = $row5['priceinv'];
                        $amountinv = $row5['amountinv'];
                        $amountinvsum += $amountinv;//суммируем суммы по всем счетам
                        $margin = round(((1 - ($amountinvsum / $amountauc )) * 100), 0);
                        $marginprice = round(((1 - ($priceinv / $priceauc )) * 100), 0);

                    }echo "Номер счета: " . $numinv . "<br/>
                            Дата счета: " . $dateinv . "<br/>
                            Количество по позиции счета (уп.): " . $qtyinv . "<br/>
                          <!--  Sku код: " . $skucode . "<br/>-->
                            Цена по позиции счета с НДС: " . $priceinv . "<br/>
                            Сумма по позиции счета с НДС: " . $amountinv . "<br/>
                            Маржа по аукциону: " . $margin . "% (корректна только после полной отгрузки)<br/>
<span>Маржа из расчета цены упаковки: " . $marginprice . "%</span><br/><br/>";

                }
                echo "Общий объем в упаковках по всем счетам аукциона " . $numauc . " равно " . $qtyinvsum . "<br/><br/> 
                        Общая сумма руб с НДС по всем счетам аукциона " . $numauc . " равна " . $amountinvsum . "<br/><br/>";
                //НЕ видим перемешку $amountinvsum, когда счета нет, и видим, когда счет есть
                $a = $qtyinvsum - $qtyauc;
                if ($a > 0) {
                    echo '<span>ВНИМАНИЕ: превышено количество по товарной позиции на ' . $a . ' упаковок.</span><br/>
                    Сотрудник тендерного направления: <a href="mailto:' . $useremail . '?subject=Аукцион ' . $numauc . ' ' . $prodname . ' превышение ' . $a . ' уп.">Уведомить</a><br/>';
                } elseif ($a < 0) {
                    echo 'Не допоставлено ' . $a . ' упаковок.';
                } elseif ($a = 0) {
                    echo 'Количество упаковок в спецификации и в инвойсе совпадает.';
                } echo '<br/>
                    <p><a href="inputinv.php?numauc='.$numauc.'&skucode='.$skucode.'">Ввести инвойс</a></p>
           
                <br/>
                    <p><a href="milestone.php"/>Вернуться</a></p>
                    </div>';
                }
        } else echo '<div class="row" id="form"><p><span>Такого аукциона нет в базе данных. Уведомите тендерного КАЕ.</span></p>
        <p><a href="checksupply.php"/>Вернуться</a></p></div>';

    } else echo '<div class="row" id="form"><p><span>Введно не достаточно данных. Повторите ввод.</span></p>
        <p><a href="checksupply.php"/>Вернуться</a></p></div>';
}
// echo 'Ваш id: ' . $_SESSION['userid'];'
?>
