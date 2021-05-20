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
    $result = $mysql->query("SELECT * FROM `user` WHERE `login` = '$user_login' AND `pass` = '$user_pass'");
    $user = $result->fetch_assoc();
    if(count($user) == 0): //========= Проверка на существование аккаунта =========//
    {
        echo "Неверный логин или пароль";
        exit();
    }
    else: //========= Добавление куки, если пользователь проходит аутентификацию =========//
    {
        $user = array('id' => $user['id'],'login' => $user['login'], 'elo' =>  $user['elo'], 'solved' => $user['solved']);
        setcookie('user', serialize($user), time() + 3600, "/");
    }
    endif;
    //========= Закрытие соединения с БД =========//
    $mysql->close();
    //========= Переход на главную страницу =========//
    header('Location: /')
?>