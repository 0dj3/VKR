<?php
    ini_set("display_errors", 1);
    error_reporting(-1);
    require_once "./blocks/connect.php";
    require_once "./blocks/functions.php";

    $tests = get_tests(); 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главное меню</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="main.php" class="center">
        <button class="btn btn-success" style="margin-top: 10px; margin-bottom: 10px;">Назад</button>
    </form>
    <div class = "warp type-form">
        <p><h2 class = "center">Добавление темы</h2></p>
        <form action="blocks/add/addtheme.php" method="POST">
            <input type="text" class="form-control" name="theme" placeholder="Введите количество ответов"required><br>
            <button class="center btn btn-success">Добавить</button>
        </form>
    </div>
    <div class = "warp type-form">
        <p><h2 class = "center">С вариантами ответов</h2></p>
        <form action="add.php" method="POST">
            <p>Количество ответов:</p>
            <input type="number" class="form-control" name="answer-count1" placeholder="Введите количество ответов" min="0" max="4" required><br>
            <button class="center btn btn-success">ОК</button>
        </form>
        <form action="blocks/add/addtype1.php" method="POST">
            <input type="hidden" name="answer-count1" value="<?=$_POST['answer-count1']?>">
            <p>Выберите тест:</p>
            <select class="form-select" name="tests1" id="test1">
                <?php foreach ($tests as $test): ?>
                    <option value="<?=$test['id']?>"><?=$test['test_name']?></option>
                <?php endforeach; ?>
            </select><br>
            <p>Сложность задачи:</p>
            <input type="number" class="form-control" name="answer-complexity" placeholder="Сложность задачи от 1 до 10" min="1" max="10" required><br>
            <p class = "t">
                <p>Условие задачи:</p>
                <input type="text" class="form-control" name="type1-t" placeholder="Введите условие задачи" required><br>
            </p>
            <p>Ответы:</p>
            <?php /*if(isset($_POST['answer-count1']))*/ for ($i = 1; $i <= 4/*$_POST['answer-count1']*/; $i++): ?>
                <div class="form-group row type-a">
                    <div class="input-group mb-3">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="radio" name='type1-correct<?=$i?>' id="<?=$i?>">
                        </div>
                        <input type='text' class="form-control" name='type1-a<?=$i?>' placeholder='Введите ответ №<?=$i?>' required>
                    </div>
                </div>
            <?php endfor; ?>
            <button class="center btn btn-success">Добавить</button>
        </form>
    </div>
    <div class = "warp type-form">
        <p><h2 class = "center">С открытым ответом</h2></p>
        <form action="blocks/add/addtype2.php" method="POST">
            <select class="form-select" name="tests2" id="test2">
                <?php foreach ($tests as $test): ?>
                    <option value="<?=$test['id']?>"><?=$test['test_name']?></option>
                <?php endforeach; ?>
            </select><br>
            <p>Сложность задачи:</p>
            <input type="number" class="form-control" name="answer-complexity" placeholder="Сложность задачи от 1 до 10" min="1" max="10" required><br>
            <p class = "t">
                <p>Условие задачи:</p>
                <input type="text" class="form-control" name="type2-t" placeholder="Введите условие задачи" required><br>
            </p>
            <p>Правильный ответ:</p>
            <p class = "a">
                <input type='text' class="form-control" name='type2-a' placeholder='Введите ответ' required>
            </p>
            <button class="center btn btn-success">Добавить</button>
        </form>
    </div>
    <div class = "warp type-form">
        <p><h2 class = "center">С картинкой и вариантами</h2></p>
        <form action="add.php" method="POST">
            <p>Количество ответов:</p>
            <input type="number" class="form-control" name="answer-count2" placeholder="Введите количество ответов" min="0" max="4" required><br>
            <button class="center btn btn-success">ОК</button>
        </form><br>
        <form action="blocks/add/addtype3.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="answer-count2" value="<?=$_POST['answer-count2']?>">
            <select class="form-select" name="tests3" id="test3">
                <?php foreach ($tests as $test): ?>
                    <option value="<?=$test['id']?>"><?=$test['test_name']?></option>
                <?php endforeach; ?>
            </select><br>
            <p>Сложность задачи:</p>
            <input type="number" class="form-control" name="answer-complexity" placeholder="Сложность задачи от 1 до 10" min="1" max="10" required><br>
            <p class = "t">
                <p>Условие задачи:</p>
                <input type="text" class="form-control" name="type3-t" placeholder="Введите условие задачи" required><br>
                <p>Загрузите изображение:</p>
                <input type="file" class="form-control-file" name="type3-img" accept="image/jpeg,image/png,image/gif" required><br>  
            </p>
            <p>Ответы:</p>
            <?php /*if(isset($_POST['answer-count2']))*/ for ($i = 1; $i <= 4/*$_POST['answer-count2']*/; $i++): ?>
                <div class="form-group row type-a">
                    <div class="input-group mb-3">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="radio" name='type3-correct<?=$i?>' id="<?=$i?>">
                        </div>
                        <input type='text' class="form-control" name='type3-a<?=$i?>' placeholder='Введите ответ №<?=$i?>' required>
                    </div>
                </div>
            <?php endfor; ?>
            <button class="center btn btn-success">Добавить</button>
        </form>
    </div>
</body>
</html>