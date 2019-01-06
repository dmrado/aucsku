<?php
session_start();
error_reporting(E_ALL);
//Здесь тендер КАЕ вводит данные по sku в аукционе, не знаю как поставить блокировку $role!=3, !=0;
include_once ('func.php');
$conn=mysqli_connect($server,$login,$password);
mysqli_query($conn, "SET NAMES UTF8");
mysqli_select_db($conn, $database);
$numauc='';
$amountauc='';
$msg1='';
$msg2='';
$msg3='Не правильный номер аукциона. Повторите ввод!';
//if isset $

//пара numauc skucode уникальна,
//подключаемся к базе дабы проверить есть ли в базе товарищ из переменной $_SESSION
$userlogin=mysqli_real_escape_string($conn, $_SESSION['userlogin']);
$userpassword=mysqli_real_escape_string($conn, $_SESSION['userpassword']);
//здесь сотрудник, который залогинился, еще раз перепроверяем его наличие
$result=mysqli_query($conn, "SELECT * FROM users WHERE userlogin='".$userlogin."' && userpassword='".$userpassword."'");
$count=mysqli_num_rows($result);
// и если есть выводим ему все данные аукциона и счетов к нему.
if ($count>0) {
    if (isset($_POST['numauc']) /*&& is_numeric($_POST['numauc'])*/ && isset($_POST['dateauc']) && isset($_POST['skucode']) /*&& is_numeric($_POST['skucode'])*/ && isset($_POST['qtyauc']) /*&& is_numeric($_POST['qtyauc'])*/ && isset($_POST['priceauc']) && isset($_POST['userid']) && isset($_POST['law']) /*&& ($_SESSION['$role'] !== 3) && ($_SESSION['$role'] !== 0)*/) {

        $numauc = trim(mysqli_real_escape_string($conn, $_POST['numauc']));
        $dateauc = mysqli_real_escape_string($conn, $_POST['dateauc']);
        $skucode = trim(mysqli_real_escape_string($conn, $_POST['skucode']));
        $qtyauc = trim(mysqli_real_escape_string($conn, $_POST['qtyauc']));

        $userid = mysqli_real_escape_string($conn, $_POST['userid']);//может здесь сразу поставить "'.$_SESSION['userid'].'" ?
        $priceauc = trim(mysqli_real_escape_string($conn, $_POST['priceauc']));
        $priceauc = str_replace(",", ".", $priceauc);//меняем запятую на точку

        if (isset($_POST['amountauc']) && ($_POST['amountauc']!=='')) {
        $amountauc = trim(mysqli_real_escape_string($conn, $_POST['amountauc']));
        if(strstr($amountauc, ",")) {
	        $amountauc = str_replace(".", "", $amountauc);
	        $amountauc = str_replace(",", ".", $amountauc);
        }
	    $amountauc = preg_replace("/ +/", "", $amountauc); //убираем пробелы
	    }
	    else {
			$amountauc = $qtyauc * $priceauc;
	    }

        $lawauc = mysqli_real_escape_string($conn, $_POST['law']);
        if ($lawauc == '44'){

	        if (!preg_match("/^[0-9]{19}$/", $numauc)){
                Header("Location: inputauc.php");
	            echo
                '<span>Не правильный номер аукциона записался в базу. Запросите коррекцию!</span>'; //или так или наоборот в элс надо это написать - попробуй
	        }
	        }//else echo 'Не правильно набран номер аукциона';

        if ($lawauc == '223') {
	        if (!preg_match("/^[0-9]{11}$/", $numauc)){
		        echo '<span>Не правильный номер аукциона записался в базу. Запросите коррекцию!</span>';
	        }
            }//else echo 'Не правильно набран номер аукциона';

        $remarkauc = mysqli_real_escape_string($conn, $_POST['remarkauc']);

        $query = mysqli_query($conn, "SELECT * FROM auction WHERE numauc='" . $numauc . "' && skucode='" . $skucode . "'");
        $count2 = mysqli_num_rows($query);
        if ($count2 > 0) {
            $msg1='<span><strong>Такой продукт уже введен в аукцион № "' . $numauc . '"<br/> <!--Вы хотите <a href="updateauc.php">внести измение</a> в данные по аукциону?</strong></span>-->';
        }
        else {
            $result = mysqli_query($conn, "INSERT INTO `auction` (`auctionid`, `numauc`, `dateauc`, `skucode`, `qtyauc`, `priceauc`, `amountauc`, `userid`, `lawauc`, `remarkauc`) VALUES (NULL,'" . $numauc . "','" . $dateauc . "','" . $skucode . "','" . $qtyauc . "','" . $priceauc . "','" . $amountauc . "','" . $_SESSION['userid'] . "','" . $lawauc . "','" . $remarkauc . "')");
            if ($result) { echo '<span>Информация об аукционе №' . $numauc . ' успешно внесена.</span><br/>';
            } else { echo '</span>Информация об аукционе №' . $numauc . ' не внесена в базу данных, повторите ввод.</span><br/>';
            }
        }
    }

            echo $form3='<!DOCTYPE html>
            <head>
                <meta charset="utf-8"/>
                <title>Auction input</title>
                <link href="css/form_login.css" type="text/css" rel="stylesheet"/>
            </head>
            <body>
            <script>
				function confirm_auc() {
				    var qtyauc = document.getElementById("qtyauc").value;
				    var priceauc = document.getElementById("priceauc").value;
					var amountauc=qtyauc*priceauc;
				return confirm(\'Сумма по аукциону: \' + amountauc);
			}
			</script>
            

            <div id="form">
                    '.$msg1.$msg2.'
                    <h1>Форма ввода выигранного аукциона</h1>
                    <h2>Введите данные аукциона</h2>
    
                <form action="inputauc.php" method="post"/>
    
                    <p><label for="numauc">аукцион
                    <input type="text" id="text" name="numauc" value="' . $numauc . '" placeholder="номер аукциона"/>
                    </label>
                    
                    <label for="law">Закон
                    <input type="radio" id="text" name="law" value="44" checked/>44-ФЗ
                    <input type="radio" id="text" name="law" value="223"/>233-ФЗ
                    </label></p>
                    
                    <p><label for="skucode">продукт
                    <input type="text" id="text" name="skucode" value="" maxlength="7" placeholder="sku код семь цифр"/>
                    </label>
    
                    <label for="dateauc">дата
                    <input type="date" id="text" name="dateauc" value="" placeholder="yyyy.mm.dd"/>
                    </label></p>
    
                    <p><label for="qtyauc"><br/>Финальное количество упаковок по спецификации ГК<br/>
                    <input type="text" id="qtyauc" name="qtyauc" value="" placeholder="целое число" "/>
                    </label></p>
    
                    <p><label for="priceauc"><br/>Цена позиции по спецификации ГК с НДС<br/>
                    <input type="text" id="priceauc" name="priceauc" value="" placeholder="до 2 знаков после запятой" />
                    </label></p>
                
                    <p><label for="amountauc"><br/>Сумма по позиции по спецификации ГК с НДС<br/>
                    <input type="text" id="text" name="amountauc" value="'.$amountauc.'"/>
                    </label></p> 
                    
                    <!-- я убрал ввод id пользователя чтобы они не писали чужие. да и не знают они свои id
                    <p><label for="userid"><br/>Ваш id<br/>-->
                    <input type="hidden" id="text" name="userid" value="' . $_SESSION['userid'] . '"/>
                   
                    <br/>
                    <p><label for="remarkauc">Примечание<br/>
                    <textarea rows=4 cols=50 name="remarkauc" value="text" placeholder="Например: общее количество в спецификации ХХХ упаковок"> </textarea></label></p>
                    <br/>
    
                    <input type="submit" name="submit" value="Отправить" onclick="return confirm_auc(); this.submit();"/>
                    <br/>
                    <p><a href="milestone.php"/>Вернуться</a></p>
                </form>
                </div>
            </body>';
}
else {
    session_destroy();
    Header("Location: index.html");
}