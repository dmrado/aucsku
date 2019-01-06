<?php

//Проверка наличия тройки номер аукциона номер счета код продукта, если есть, то не записываем такой счет иначе задвоения
/*$result4 = mysqli_query($conn, "SELECT * FROM invoice WHERE numauc = '".$numauc."' && numinv = '".$numinv."' && skucode = '".$skucode."'");
$countinv = mysqli_num_rows($result4);
if ($countinv = 0) {

else{ */

echo '<span>ВНИМАНИЕ: в базе уже есть счет номер: '.$numinv.' и продукт: '.$skucode.'  к аукциону '.$numauc.'! <br/> ЕСЛИ вводите дробную поставку заводите новый счет!</span>';
?>