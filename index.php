<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Вход</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container type-form">
        <?php
            if($_COOKIE['user'] == ''):
        ?>
        <h1>Форма авторизации</h1>
        <form action="validation-form/auth.php" method="post">
            <input type="text" class="form-control" name="login"
            id="login" placeholder="Введите логин" required><br>
            <input type="password" class="form-control" name="pass"
            id="pass" placeholder="Введите пароль" required><br>
            <a href="/register.html">Зарегистрироваться</a><br>
            <button class="btn btn-success" type="submit">Авторизоваться</button>
        </form>
        <?php else: ?>
            <meta http-equiv="refresh" content="0; url=main.php">
        <?php endif;?>
    </div>
</body>
</html>