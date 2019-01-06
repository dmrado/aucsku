<?php
session_start();
//error_reporting(E_ALL);
//Здесь тендер КАЕ вводит данные по sku в аукционе, не знаю как поставить блокировку $role!=3, !=0;
include_once ('func.php');
$conn=mysqli_connect($server,$login,$password);
mysqli_query($conn, "SET NAMES UTF8");
mysqli_select_db($conn, $database);
$amountinv='';

if (isset ($_GET['numauc']) && is_numeric($_GET['numauc']) && isset ($_GET['skucode']) && is_numeric($_GET['skucode'])) {
    $numauc = trim($_GET['numauc']);
    $skucode = trim($_GET['skucode']);

}else {
    $numauc='';
    $skucode='';
}
$dateauc='';
$distrid='';

    $form = '<!DOCTYPE html>
            <head>
                <meta charset="utf-8"/>
                <title>Invoice input</title>
                <link href="css/form_login.css" type="text/css" rel="stylesheet"/>
            </head>
            <body>
          <script type="text/javascript">
				function confirm_inv() {
				    var qtyinv = document.getElementById("qtyinv").value;
				    var priceinv = document.getElementById("priceinv").value;
					var amountinv=qtyinv*priceinv;
				return confirm(\'Сумма по инвойсу: \' + amountinv);
			}
			</script>
          
			<div id="form">
                    <h1>Форма ввода инвойса</h1>
                    <h2>Введите данные инвойса для аукциона ' . $numauc . ' <br/></h2>
    
                <form action="inputinv.php" method="post"/>
    
                    <p><label for="numauc">Аукцион
                        <input type="text" id="text" name="numauc" value="' . $numauc . '"/>
                        </label>
                        
                        <label for="skucode">skucode
                        <input type="text" id="text" name="skucode" value="' . $skucode . '" maxlength="7"/>
                        </label></p>
                        
                       <p><label for="seria">Серия
                        <input type="text" id="text" name="seria" value="" maxlength="7"/>
                        </label>
                        <label for="distrid">дистрID
                        <input type="text" id="text" name="distrid" value="" maxlength="10"/>
                        </label></p>
        
                        <p><label for="numinv">инвойс №
                        <input type="text" id="text" name="numinv" value="" placeholder="номер инвойса"/>
                        </label>
                        
                        <label for="dateinv">инвойс
                        <input type="date" id="text" name="dateinv" value="" placeholder="выберите дату"/>
                        </label></p>
                
                        <p><label for="qtyinv"><br/>Общее количество упаковок по товарной позиции в инвойсе<br/>
                        <input type="text" id="qtyinv" name="qtyinv" value="" placeholder="целое число" />
                        </label></p>
                        
                        <p><label for="priceinv"><br/>Цена позиции инвойса ГК с НДС<br/>
                        <input type="text" id="priceinv" name="priceinv" value="" placeholder="до 2 знаков после запятой" />
                        </label></p>
                        
                        <p><label for="amountinv"><br/>Сумма по позиции инвойса с НДС<br/>
                        <input type="text" id="amountinv" name="amountinv" value="'. $amountinv.'"/>
                        </label></p>

                        <p><label for="remarkinv"><br/>Ремарка<br/>
                        <textarea id="text" rows="4" cols="40" name="text" maxlength="250"></textarea>
                        </label></p>
                        
                       <input type="hidden" id="text" name="userid" value="' . $_SESSION['userid'] . '"/>
                     
                       <input type="submit" name="submit" value="Отправить" onclick="return confirm_inv(); this.submit();"/>
                       <br/>
                       <p><a href="milestone.php"/>Вернуться</a></p></form>';

    if (isset($_SESSION['userlogin']) && isset($_SESSION['userpassword']) && isset($_REQUEST['numauc']) && is_numeric($_REQUEST['numauc']) && isset($_POST['skucode']) && is_numeric($_POST['skucode'])) {
        $numauc = trim(mysqli_real_escape_string($conn, $_REQUEST['numauc']));
        $userlogin = mysqli_real_escape_string($conn, $_SESSION['userlogin']);
        $userpassword = mysqli_real_escape_string($conn, $_SESSION['userpassword']);
        $result = mysqli_query($conn, "SELECT * FROM users WHERE userlogin='" . $userlogin . "' && userpassword='" . $userpassword . "'");
        $count = mysqli_num_rows($result);
        if ($count == 0) {
            session_destroy();
            Header("Location: index.html");
        } else
        echo $form;


        if (isset ($_REQUEST['numauc']) && is_numeric($_REQUEST['numauc']) && isset($_POST['numinv']) && is_numeric($_POST['numinv']) && isset($_POST['dateinv']) && isset($_POST['skucode']) && is_numeric($_POST['skucode'])  && isset($_POST['seria']) && isset($_POST['qtyinv']) && is_numeric($_POST['qtyinv']) && isset($_POST['priceinv']) && isset($_POST['amountinv'])) {
            $numinv = trim(mysqli_real_escape_string($conn, $_POST['numinv']));
            $numauc = trim(mysqli_real_escape_string($conn, $_REQUEST['numauc']));
            $dateinv = mysqli_real_escape_string($conn, $_POST['dateinv']); //в дате точки - значит не нумерик, а стринг
            $qtyinv = trim(mysqli_real_escape_string($conn, $_POST['qtyinv']));
            $skucode = trim(mysqli_real_escape_string($conn, $_POST['skucode']));
            $seria = trim(mysqli_real_escape_string($conn, $_POST['seria']));
            $seria = preg_replace("/ +/", "", $seria);//убираем пробелы в середине
            $priceinv = trim(mysqli_real_escape_string($conn, $_POST['priceinv']));
            $priceinv = str_replace(",",".",$priceinv);//меняем запятую на точку

            if (isset($_POST['amountinv']) && ($_POST['amountinv']!=='')) {
                $amountinv = trim(mysqli_real_escape_string($conn, $_POST['amountinv']));
                if(strstr($amountinv, ",")) {
                    $amountinv = str_replace(".", "", $amountinv);
                    $amountinv = str_replace(",", ".", $amountinv);
                }
                $amountinv = preg_replace("/ +/", "", $amountinv); //убираем пробелы
            }
            else {
                $amountinv = $qtyinv * $priceinv;
            }

            $distrid = trim(mysqli_real_escape_string($conn, $_POST['distrid']));
            $distrid = preg_replace("/ +/", "", $distrid);//убираем пробелы в середине
            $remarkinv = mysqli_real_escape_string($conn, $_POST['text']);
            //и раскидываю по двум таблицам invoice и invoiceprice, ошибка будет когда они перезапишуся при новом вводе (для избегания этого и ввден НЮАНС).



//код выпадающего окна с выбором дистрибутора по дистрибуторайди
            // $distrid=intval($_POST['distrid']);


            $result3=mysqli_query($conn, "SELECT * FROM distribs WHERE distrid='" . $distrid . "'");
            while($row3=mysqli_fetch_array($result3)){
                $distr=$row3['distr'];

            }
            //проверяем наличие тройки номер аукциона, номер счета, код продукта и если такой уже есть, то не даем записать в базу, требуем завести новый счет
            $result4 = mysqli_query($conn, "SELECT * FROM invoice WHERE numauc = '".$numauc."' && numinv = '".$numinv."' && skucode = '".$skucode."'");
            $countinv = mysqli_num_rows($result4);
            if ($countinv != 0) {
                echo '<span>ВНИМАНИЕ: в базе уже есть счет номер: '.$numinv.' и продукт: '.$skucode.'  к аукциону '.$numauc.'! <br/> Если вводите дробную поставку, заводите новый счет!</span>';
            }else

                $result1 = mysqli_query($conn, "INSERT INTO `invoice` (`invoiceid`, `numinv`, `numauc`, `dateinv`, `qtyinv`, `skucode`, `userid`, `distr`, `remarkinv`) VALUES (NULL, '" . $numinv . "','" . $numauc . "','" . $dateinv . "', '" . $qtyinv . "', '" . $skucode . "', '" . $_SESSION['userid'] . "', '". $distr ."', '" . $remarkinv . "')");

            $result2 = mysqli_query($conn, "INSERT INTO `invoiceprice` (`invpriceid`, `priceinv`, `amountinv`, `skucode`, `numauc`, `qtyinv`, `numinv`, `seria`) VALUES (NULL, '" . $priceinv . "','" . $amountinv . "', '" . $skucode . "', '" . $numauc . "', '" . $qtyinv . "', '" . $numinv . "', '" .$seria. "')");

            //проверяю записанность через true и вывожу сообщение
            if ($result1 && $result2) {
                echo '</span>Информация об инвойсе ' . $numinv . ' успешно внесена.</span>';
            } else {
                echo '</span>Информация об инвойсе ' . $numinv . ' не внесена в базу данных.</span>';
            }
        } else {
                echo '</span>Такой аукцион еще не внесен. </br>Уведомите тендерного КАЕ о необходимости выставить счет.</span>';

        }
    }
    else echo $form;

        echo '</div></body>';
?>