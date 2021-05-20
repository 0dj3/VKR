<?php
    //========= Получение данных с action =========//
    $user_login = filter_var(trim($_POST['login']), 
    FILTER_SANITIZE_STRING);
    $user_pass = filter_var(trim($_POST['pass']), 
    FILTER_SANITIZE_STRING);
    //========= Хеширование пароля =========//
    $user_pass = md5(($user_pass + "salt")."hashkey");
    //========= Подключение к БД =========//
    require("../blocks/connect.php");
    $result = $mysql->query("SELECT * FROM `user` WHERE `login` = '$user_login'");
    $user = $result->fetch_assoc();
    if(count($user) == 0): //========= Добавление нового пользователя, если логин не занят =========//
    {
        $mysql->query("INSERT INTO `user` (`login`, `pass`) VALUES('$user_login','$user_pass')");     
    }
    else: 
    {
        echo "Такой пользователь уже существует";
        exit();
    }
    endif;
    //========= Закрытие соединения с БД =========//
    $mysql->close();
    //========= Переход на главную страницу =========//
    header('Location: /')
?>