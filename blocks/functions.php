<?php
    // Распечатывание массива для проверки
    function print_arr($arr){
        echo'<pre>' . print_r($arr,true) . '</pre>';
    }
    // Создание массива с названиями тестов
    function get_tests(){
        global $mysql;
        $query = "SELECT * FROM test WHERE enable='1'";
        $res = mysqli_query($mysql, $query);
        $data = array();
        while($row = mysqli_fetch_assoc($res)){
            $data[]=$row;
        }
        return $data;
    }
    // Создание массива с вопросами 
    function get_test_data($test_id){
        if(!$test_id) return;
        $user = unserialize($_COOKIE['user']);
        $user_id = $user['id'];
        $complexity = progress($test_id, $user_id);
        global $mysql;
        $query = "SELECT t.task, t.parent_test, a.id, a.answer, a.parent_task
        FROM tasks t 
        LEFT JOIN answers a
            ON t.id = a.parent_task
        LEFT JOIN test
            ON test.id = t.parent_test        
                WHERE t.parent_test = $test_id AND test.enable = '1' AND t.complexity = $complexity";
        $res = mysqli_query($mysql,$query);
        $data = null;
        while($row = mysqli_fetch_assoc($res)){
            if(!$row['parent_task']) return false;
            $data[$row['parent_task']][0] = $row['task'];
            $data[$row['parent_task']][$row['id']] = $row['answer'];
        } 
        return $data;
    }
    // Возвращение типа вопроса
    function get_task_type($task_id){
        if(!$task_id) return;
        global $mysql;
        $query = "SELECT type FROM tasks WHERE id = $task_id";
        $res = mysqli_query($mysql,$query);
        $row = mysqli_fetch_assoc($res);
        $test_type = $row['type'];

        return $test_type;
    }
    // Возвращение названия картинки
    function get_image_path($task_id){
        if(!$task_id) return;
        global $mysql;
        $query = "SELECT name FROM images WHERE parent_task = $task_id";
        $res = mysqli_query($mysql,$query);
        $row = mysqli_fetch_assoc($res);
        $image_path = $row['name'];

        return $image_path;
    }
    // Создание табов
    function pagination($count_questions, $test_data){
        $keys = array_keys($test_data);
        $pagination = '<div class = "pagination">';
        for($i = 1; $i <= $count_questions; $i++){
            $key = array_shift($keys);
            if($i == 1){
                $pagination .= '<a class="nav-active" href="#task-'.$key.'">'.$i.'</a>';
            }
            else{
                $pagination .= '<a href="#task-'.$key.'">'.$i.'</a>';
            }
        }
        $pagination .= '</div>';
        return $pagination;
    }
    // Получение ответов
    function get_correct_answers($test){
        if(!$test) return false;
        $user = unserialize($_COOKIE['user']);
        $user_id = $user['id'];
        global $mysql;
        $complexity = progress($test, $user_id);
        $query = "SELECT t.id AS task_id, a.id AS answer_id, a.answer AS answer
        FROM tasks t
        LEFT JOIN answers a
            ON t.id = a.parent_task
        LEFT JOIN test
            ON test.id = t.parent_test
                WHERE t.parent_test = $test 
                    AND a.correct_answer = '1'
                    AND test.enable = '1'
                    AND t.complexity = $complexity";
        $res = mysqli_query($mysql, $query);
        while($row = mysqli_fetch_assoc($res)){
            $data[$row['task_id']] = $row['answer'];
        }
        return $data;
    }
    // Формирование результатов теста
    function get_test_result($test_all_data, $result, $post){
        global $mysql;
        //Добавление сложности задачи в результаты тестов
        foreach($result as $t => $a)
        {
            $query = "SELECT `complexity` FROM tasks WHERE id = $t";
            $res = mysqli_query($mysql,$query);
            $row = mysqli_fetch_assoc($res);
            $test_all_data[$t]['complexity'] = $row['complexity'];
        }
        //заполнение массива $test_all_data ответами
        foreach($result as $t => $a){
            $test_all_data[$t]['correct_answer'] = $a;
            //Проигнорированные вопросы
            if(!isset($post[$t])){
                $test_all_data[$t]['incorrect_answer'] = 0;
            }
        }
        //Проверка на неверный ответ
        foreach($post as $t => $a){
            if($test_all_data[$t]['correct_answer'] == $a) continue;
            //Удаление нежелательных инъекций - вопросы
            if(!isset($test_all_data[$t])){
                unset($post[$t]);
                continue;
            }
            //Удаление нежелательных инъекций - ответы
            if(!isset($test_all_data[$t][$a])){
                $test_all_data[$t]['incorrect_answer'] = 0;
                continue;
            }
            // Неверные ответы
            if($test_all_data[$t]['correct_answer'] != $a){
                $test_all_data[$t]['incorrect_answer'] = 0;
            }
        }
        return $test_all_data;
    }
    // Вывод результатов теста
    function print_result($test_result,$test_id){
        global $mysql;
        $all_count = count($test_result);//Кол-во вопросов
        $incorrect_answers_count = 0;//Кол-во неправильных ответов
        //Данные пользователя
        $user = unserialize($_COOKIE['user']);
        $elo = $user['elo'];
        $user_id = $user['id'];
        $user_login = $user['login'];
        $user_solved = $user['solved'];
        $old_elo = $elo;
        //Подсчет количества неправильных ответов
        foreach($test_result as $item){
            if(isset($item['incorrect_answer'])) $incorrect_answers_count++;
        }
        $correct_answers_count = $all_count - $incorrect_answers_count;//Кол-во правильных ответов
        $user_solved = $user_solved + $correct_answers_count;//Обновление количества правильных ответов пользователя 
        $percent = round(($correct_answers_count / $all_count * 100),2);//Процент правильных ответов
        //Подсчет рейтинга пользователя
        foreach($test_result as $item){
            if(isset($item['incorrect_answer'])){
                $elo -= $item['complexity'] * 10;
                if($elo <= 0) $elo = 0;
            }
            else{
                $elo += $item['complexity'] * 10;
            }
        }
        $elo_dif = abs($old_elo - $elo);
        // Вывод результатов теста
        $print_res = '<div class="questions">';
            $print_res .= '<div class="count-res">';
                $print_res .= "<p>Всего вопросов: <b>{$all_count}</b></p>";
                $print_res .= "<p>Из них отвечено верно: <b>{$correct_answers_count}</b></p>";
                $print_res .= "<p>Из них отвечено неверно: <b>{$incorrect_answers_count}</b></p>";
                $print_res .= "<p>Процент верных ответов: <b>{$percent}%</b></p>";
                if($old_elo > $elo){
                    $print_res .= "<p>Вы потеряли <b>{$elo_dif}</b> рейтинга. Ваш рейтинг: <b>{$elo}</b></p>";
                }
                else{
                    $print_res .= "<p>Вы получили <b>{$elo_dif}</b> рейтинга. Ваш рейтинг: <b>{$elo}</b></p>";
                }
            $print_res .= '</div>';
            // Обзор результатов теста
            foreach($test_result as $id_task => $item){
                $correct_answer = $item['correct_answer'];
                $incorrect_answer = null;
                if(isset($item['incorrect_answer'])){
                    $incorrect_answer = $item['incorrect_answer'];
                    $class = "task-res error";
                }
                else{
                    $class = "task-res ok";
                }
                $print_res .= "<div class='$class'>";
                    foreach($item as $id_answer => $answer){//Массив ответов
                        if($id_answer === 0){
                            //вопрос
                            $print_res .= "<p class='t'>$answer</p>";
                        }elseif(is_numeric($id_answer)){
                            //ответ
                            if($answer == $correct_answer){
                                //верный ответ
                                $class = 'a ok-ans';
                            }elseif($answer == $incorrect_answer){
                                //неверный ответ
                                $class = 'a error-ans';
                            }else{
                                $class = 'a';
                            }
                            $print_res .= "<p class='$class'>$answer</p>";
                        }
                    }
                $print_res .= '</div>';
            }
        $print_res .= '</div>';
        $query = "UPDATE `user` SET `elo`= $elo,`solved`= $user_solved WHERE `login` = '$user_login';";
        $res = mysqli_query($mysql, $query);
        if ($percent < 50){
            $print_res .= '<p>Процент верных ответов меньше, чем 50%. Попробуйте пройти тест заново, чтобы пройти на следующую сложность</p>';
        }
        else{
            $query = "UPDATE `progress` SET `complexity` = `complexity` + 1 WHERE `user_id` = $user_id AND `test_id` = $test_id;";
            $res = mysqli_query($mysql, $query);
        }
        $user = array('id' => $user['id'], 'login' => $user['login'], 'elo' =>  $elo, 'solved' => $user_solved);
        setcookie('user', serialize($user), time() + 3600, "/");
        $print_res .= ' <form action="index.php">
                                <p><button class="btn btn-success center">Закрыть тест</button></p>
                        </form>';
        return $print_res;
    }

    function progress($test_id, $user){
        if(!$test_id) return;
        if(!$user) return;
        global $mysql;
        $query = "SELECT complexity FROM `progress` WHERE user_id = $user AND test_id = $test_id";
        $res = mysqli_query($mysql, $query);
        $row = mysqli_fetch_assoc($res);
        if(isset($row)){
        $progress = $row['complexity'];
        return $progress;
        }
        else{
            $query = "INSERT INTO `progress`(`user_id`, `test_id`, `complexity`) VALUES ($user,$test_id,1)";
            $res = mysqli_query($mysql,$query);
            return 1;
        }
    }