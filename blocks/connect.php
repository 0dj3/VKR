<?php
    //========= Данные для подключения к БД =========//
    $db_user = 'root';
    $db_pass = 'root';
    $db_name = 'database';
    $db_host = 'localhost';
    $db_port = 3307;
    //========= Подключения к БД =========//
    $mysql = @mysqli_connect("$db_host:$db_port",$db_user,$db_pass,$db_name);
?>
