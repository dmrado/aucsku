<?php
// Подключаем файл для соединения с СУБД MySQL
require_once( 'database.php' );
// Подключаем файл, в котором будем объявлять пользовательские функции
require_once( 'functions.php' );
?>
<!-- Пишем в рамках стандарта HTML5 -->
<!DOCTYPE html>
<html>
<head>
	<title>Выбор марки и модели автомобиля</title>
	<!-- Подключаем библиотеку jQuery -->
	<script src="//libs.raltek.ru/libs/jquery/1.8.3/js/jquery-1.8.3.js"></script>
	<!-- Подключаем таблицу стилей -->
	<link href="style.css" rel="stylesheet" type="text/css" />
	<!-- Подключаем JavaScript-файл с нашим сценарием, который и будет получать данные об автомобилях -->
	<script src="scripts.js"></script>
</head>
<body>
	<!-- Создаем контейнер-обертку для нашей формы -->
	<div id="car_producers_wrapper">
		<!-- Сама форма -->
		<form name="car_producers" id="car_producers" >
			<!-- Контейнер для поля выбора производителя -->
			<div class="row">
				<!-- Метка поля производителей автомобилей -->
				<label for="producer">Производитель автомобилей:</label>
				<!-- Раскрывающийся список производителей автомобилей -->
				<select id="producer">
					<option value="0">Выберите из списка</option>
					<?php					
					// Получаем перечень производителей в виде массива
					$aProducers = getProducers();
					
					// Для каждого элемента массива производителей автомобилей...
					foreach ( $aProducers as $aProducer ) {
						// Создаем свой элемент раскрывающегося списка
						print '<option value="' . $aProducer[ 'id' ] . '">' . $aProducer[ 'producer' ] . '</option>';
						
					}
					?>
				</select>
			</div>
			<!-- Контейнер для поля выбора модели автомобиля выбранного производителя -->
			<div class="row">
				<!-- Метка поля выбора марки автомобиля -->
				<label for="model">Марка автомобиля:</label>
				<!-- Раскрывающийся список выбора марки автомобиля выбранного производителя -->
				<!-- Изначально список пуст и неактивен -->
				<!-- Данные в нем появятся полсле выбора производителя -->
				<select id="model" disabled >
					<option value="0">Выберите из списка</option>
				</select>
			</div>
			
		</form>
	</div>
</body>
</html>