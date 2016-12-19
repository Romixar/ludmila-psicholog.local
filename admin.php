<?php
require 'db.php';


$config = new config();

// проверка авторизован ли пользователь
if(!isset($_SESSION['logged_user'])){
    //echo 'пользователь незалогинин';
    header('Location: '.$config::HOST_ADDRESS.'login.php');
    //header('Location: http://ludmila-psicholog.ru/login.php');
}
header("Content-Type: text/html; charset=utf-8");
// название страницы в массиве
$title = $_GET;

?>
<head>
    <link rel="stylesheet" href="css/style.css"/>
    <?php foreach($title as $key => $val){ ?>
    <title>Управление страницей <?= $val ?></title>
    <?php } ?>
</head>
<a href="logout.php" style="float:right; margin-right:40px">выход</a>
<h1>Админ-панель</h1>

<hr>
<div style="padding:3px 0px; background-color:#55AFE6;">
    <form action="">
    <div style="margin:auto auto; width:55%;">
        <p>
            <button type="submit" name="but1" value="Главная" class="but">Главная</button>
            <button type="submit" name="but2" value="Обо мне" class="but">Обо мне</button>
            <button type="submit" name="but3" value="Услуги" class="but">Услуги</button>
            <button type="submit" name="but4" value="Контакты" class="but">Контакты</button>
        </p>
    </div>
    </form>     
</div>

<hr>
<!--
roma
romix
admin
ПРО АДМИН ПАНЕЛЬ https://www.youtube.com/watch?v=2UpWUuA0jKs
-->
<a href="/" style="float:right; margin-right:40px" target="_blank">Открыть сайт в новом окне</a>
<?php
// подключение всех страниц
if(isset($_GET['but1'])){
    include('main.php');
}
if(isset($_GET['but2'])){
    include('about.php');
}
if(isset($_GET['but3'])){
    include('services.php');
}
if(isset($_GET['but4'])){
    include('contacts.php');
}

?>
