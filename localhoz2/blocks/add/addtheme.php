<?php
    //========= Получение данных с action =========//
    $theme = trim($_POST['theme']);
    //========= Работа с БД =========//
    require("../connect.php");
    // Добавление задания
    $test_query = "INSERT INTO `test` (`test_name`, `enable`) VALUES ('$theme', '1');"; 
    $add_test = $mysql->query($test_query);
    //========= Переход на главную страницу =========//
    header('Location: /add.php')
?>