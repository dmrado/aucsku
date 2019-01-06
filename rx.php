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
        else { echo
            '<!DOCTYPE html>
                <html>
                    <head>
                        <meta charset="utf-8"/>
                        <title>Rx</title>
                        <link href="css/form_login.css" type="text/css" rel="stylesheet"/>
                    </head>
                    <body>
                        <div id="form" class="row">
                                <h1>Проверьте поставку по графику</h1>
                            	
                            <!--только в этот раздел пускаем весь Rx для проверки отгрузок сделать action сопоставить график из shedule с датами и кол-вом по счетам по номеру аукциона-->
                            <form action="rx.php" method="GET">
                            <label for="numauc"><p>Номер аукциона</p>
                            <input type="text" name="numauc" placeholder="Введите номер аукциона" maxlength="19"/></label><br/>
                        
                            <p>skucode продукта</p>
                            <label for="skucode"><input type="text" name="skucode" placeholder="skucode продукта"/></label><br/>
                                
                        <p><label><input type="submit" name="ok" value="Вперед"></label></p>
                        <p><a href="milestone.php">Вернуться</a></p>
                        </form><br/>
                ';
        }
            if (isset($_GET['numauc']) && ($_GET['numauc']!='') && (isset($_GET['skucode']) && ($_GET['skucode']!=''))) {
                $numauc = mysqli_real_escape_string($conn, $_GET['numauc']);
                $skucode = mysqli_real_escape_string($conn, $_GET['skucode']);


                //Вытащили номер аукциона и выводим по нему его данные
                $result = mysqli_query($conn, "SELECT * FROM auction WHERE numauc='" . $numauc . "' && skucode='" . $skucode . "'");
                while ($row = mysqli_fetch_array($result)) {
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
                        $useremail = $row3['email'];//это емейл тендерного КАЕ не путать с сотрудником в глобальной переменной $_SESSION, там может быть и Игорь и его команда то есть тот кто завел логин и пароль
                        $username = $row3['username'];
                    }
                    echo '<p>Номер аукциона: ' . $numauc . '<br/>
                                Дата: ' . $dateauc . '<br/>
                                Количество в спецификации (уп.): ' . $qtyauc . '<br/>
                                Тендер-КАЕ: ' . $username . '<br/>
                                Примечание: ' . $remarkauc . '<br/>';


                    echo '---------------------------------------------------------<br/>';
                    if (isset ($numauc)) {

                        $qtyinvsum = 0;
                        $result2 = mysqli_query($conn, "SELECT * FROM invoice WHERE numauc='" . $numauc . "'");

                        //выводим все значения dateinv и qtyinv всех счетов и забубениваем их в таблицу
                        echo '<h3>Отчет о выставленных по аукциону "'.$numauc.'" счетах:</h3>
                  <div class="row">
                    <table id="inventory" width="420" cellpadding="10">
                        <tr>
                           
                            <th>№ счета</th>
                            <th>Дата</th>
                            <th>Количество (уп.)</th>
                        </tr>';
                        while ($row2 = mysqli_fetch_array($result2)) {

                            $numinv = $row2['numinv'];
                            $dateinv = $row2['dateinv'];
                            $qtyinv = $row2['qtyinv'];
                            $skucode = $row2['skucode'];


                            echo' <tr>
                          
                            <td align="center">' . $numinv . ' </td>
                            <td align="center">' . $dateinv . '</td>
                            <td align="center">' . $qtyinv . '</td>
                        </tr>';
                            $qtyinvsum += $qtyinv; //суммируем все количества по счетам

                        } echo '</div></table><br/>';

                    echo 'Всего упаковок ко всем счетам аукциона №' . $numauc . ' равно ' . $qtyinvsum . ' <br/>';
                    }
                } echo 'Обратите внимание: в базе данных только выигранные аукционы.
              
                </div></body></html>';

        } else echo 'Для выполнения запроса введено недостаточно данных. Попробуйте снова.';

?>