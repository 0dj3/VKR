<?php
    //========= Получение данных с action =========//
    $parent_test = trim($_POST['tests2']);
    $task = trim($_POST['type2-t']);
    $correct_answer = trim($_POST['type2-a']);
    $complexity = trim($_POST['answer-complexity']);
    //========= Исправление строки =========//
    $task = str_replace(["\\"," "],['\\'.'\\',"\\\ "],$task);
    //========= Работа с БД =========//
    require("../connect.php");
    // Добавление задания
    $task_query = "INSERT INTO `tasks` (`type`, `parent_test`, `complexity`, `task`) VALUES ('2', '$parent_test', '$complexity', '$task');"; 
    $add_task = $mysql->query($task_query);
    // Возвращение id добавленного задания
    $result = $mysql->query("SELECT MAX(`id`) as id FROM `tasks`");
    $parent_task_id = $result->fetch_assoc();
    $id = $parent_task_id['id'];
    // Добавление ответа
    $ans_query = "INSERT INTO `answers` (`parent_task`, `answer`, `correct_answer`) VALUES ('$id', '$correct_answer', '1');";
    $add_ans = $mysql->query($ans_query);

    //========= Переход на главную страницу =========//
    header('Location: /add.php')
?>