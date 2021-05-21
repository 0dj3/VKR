<?php
    ini_set("display_errors", 1);
    error_reporting(-1);
    require_once "./blocks/connect.php";
    require_once "./blocks/functions.php";

    $tests = get_tests();
    $user = unserialize($_COOKIE['user']);

    if(isset($_GET['test'])){
        $test_id = (int)$_GET['test'];
        $test_data = get_test_data($test_id, $user);
        if(is_array($test_data)){
            $count_questions = count($test_data);
            $pagination = pagination($count_questions, $test_data);
        }
    }

    if(isset($_POST['test'])){
        $test = $_POST['test'];
        unset($_POST['test']);
        $result = get_correct_answers($test);
        if(!is_array($result)) exit('Ошибка!');
        $test_all_data = get_test_data($test);
        $test_result = get_test_result($test_all_data,$result,$_POST);
        //print_r($_POST);
        //print_r($result);
        //print_r($test);
        print_r(print_result($test_result,$test));
        die;
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Система тестирования</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<header>
    <div class="type-form warp">
        <?php if($_COOKIE['user'] == ''):?>
            <meta http-equiv="refresh" content="0; url=index.php">
        <?php endif;?>
        <p>
            Привет <?=$user['login']?>, вы на тестовой версии сайта. 
            Ваш рейтинг равен <?=$user['elo']?>, решенных задач <?=$user['solved']?>
        </p>
    </div>
</header>
<body>
    <div class="warp">
        <div class="type-form">
            <?php if($tests): //$tests?>
                <h3>Упражнения:</h3>
                <?php foreach($tests as $test): ?>
                    <p>
                        <a href="?test=<?=$test['id']?>"><?=$test['test_name']?></a>
                    </p>
                <?php endforeach; ?>
            <?php else: //$tests?>
                <h3>Нет тестов</h3>
            <?php endif; //$tests?>
        </div>
        <div class = "content type-form">
            <?php if(isset($test_data))://(isset($test_data)?>
                <p>Всего вопросов: <?=$count_questions?></p>
                <?=$pagination?>
                <span class="none" id="test-id"><?=$test_id?></span>
                <div class="test-data">
                    <?php foreach($test_data as $id_task => $item): ?>
                        <div class="task" data-id="<?=$id_task?>" id="task-<?=$id_task?>">         
                            <?php foreach($item as $id_answer => $answer)://вопрос/ответ?> 
                                <?php if(!$id_answer): //Вопросы ?>
                                    <?php if(get_task_type($id_task)==3)://Вопрос с картинкой?>
                                        <p><img src="tmp/<?=get_image_path($id_task)?>" width="100%" alt="task-img"><p>
                                    <?php endif; //$id_answer?>
                                    <p class="t">$$\displaylines{<?=$answer?>}$$</p>
                                <?php elseif(get_task_type($id_task)==2): //Открытый ответ?>
                                    <p class="a">
                                        <input type="text" class="textinput form-control" name="task-<?=$id_task?>" id="answer-<?=$id_answer?>" 
                                        placeholder="Введите Ответ" required><br>
                                    </p>
                                <?php elseif(get_task_type($id_task)==1): //Варианты ответов ?>
                                    <p class="a">
                                        <input type="radio" class="radioinput" id="answer-<?=$id_answer?>" name="task-<?=$id_task?>" value="<?=$answer?>">
                                        <label for="answer-<?=$id_answer?>">$$<?=$answer?>$$</label>
                                    </p>
                                <?php elseif(get_task_type($id_task)==3): //Вопрос с картинкой?>
                                    <p class="a">
                                        <input type="radio" class="radioinput" id="answer-<?=$id_answer?>" name="task-<?=$id_task?>" value="<?=$answer?>">
                                        <label for="answer-<?=$id_answer?>">$$<?=$answer?>$$</label>
                                    </p>
                                <?php endif; //$id_answer?>
                            <?php endforeach;?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="button">
                    <button class="center btn btn-success" id="btn">Закончить тест</button>
                </div>
            <?php else://(isset($test_data) ?>
                Выберите тест
            <?php endif;//(isset($test_data) ?>
        </div>
        <a href="blocks/exit.php">Выход</a>
        <a href="add.php">Добавить вопросы</a>
    </div>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="scripts/scripts.js"></script>
</body>
</html>