<?php
    //========= Получение данных с action =========//
    $parent_test = filter_var(trim($_POST['tests3']), FILTER_SANITIZE_STRING);
    $task = filter_var(trim($_POST['type3-t']), FILTER_SANITIZE_STRING);
    $complexity = filter_var(trim($_POST['answer-complexity']), FILTER_SANITIZE_STRING);
    $answer_count = filter_var(trim($_POST['answer-count2']), FILTER_SANITIZE_STRING);
    //========= Исправление строки =========//
    $task = str_replace(["\\"," "],['\\'.'\\',"\\\ "],$task);
    //========= Работа с БД =========//
    require("../connect.php");
    // Добавление задания
    $task_query = "INSERT INTO `tasks` (`type`, `parent_test`, `complexity`, `task`) VALUES ('3', '$parent_test', '$complexity', '$task');"; 
    $add_task = $mysql->query($task_query);
    // Возвращение id добавленного задания
    $result = $mysql->query("SELECT MAX(`id`) as id FROM `tasks`");
    $parent_task_id = $result->fetch_assoc();
    $id = $parent_task_id['id'];
    // Добавление ответов
    for($i = 1; $i <= $answer_count; $i++){
        $ans = filter_var(trim($_POST['type3-a'.$i]), FILTER_SANITIZE_STRING);
        $ans = str_replace(["\\"," "],['\\'.'\\',"\\\ "],$ans);
        $correct = filter_var(trim($_POST['type3-correct'.$i]), FILTER_SANITIZE_STRING);
        if($correct!=''){// Проверка на выбор правильного ответа
            $ans_query = "INSERT INTO `answers` (`parent_task`, `answer`, `correct_answer`) VALUES ('$id', '$ans', '1');";
            $add_ans = $mysql->query($ans_query);
        }
        else{
            $ans_query = "INSERT INTO `answers` (`parent_task`, `answer`, `correct_answer`) VALUES ('$id', '$ans', '0');";
            $add_ans = $mysql->query($ans_query);
        }
    }
    // Добавление изображения
    if(move_uploaded_file($_FILES['type3-img']['tmp_name'], '../../tmp/'.$_FILES['type3-img']['name'])){
        $img_name = $_FILES['type3-img']['name'];
        $img_query = "INSERT INTO `images` (`name`, `parent_task`) VALUES ('$img_name', '$id');"; 
        $add_img = $mysql->query($img_query);
    }
    //========= Переход на главную страницу =========//
    header('Location: /add.php')
?>