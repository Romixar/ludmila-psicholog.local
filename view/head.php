<?php


// проверка авторизован ли пользователь
//if(!isset($_SESSION['logged_user'])){
    //echo 'пользователь незалогинин';
    //header('Location: '.$config::HOST_ADDRESS.'login.php');
    
//}
//header("Content-Type: text/html; charset=utf-8");
// название страницы в массиве
//$title = $_GET;

?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="css/style.css"/>
	<?php //if(is_array($title)){ ?>
		<?php// foreach($title as $key => $val){ ?>
			<title>Управление страницей <?php// $val ?></title>
		<?php// } ?>
	<?php //} ?>
</head>
<body>
<a href="logout.php" style="float:right; margin-right:40px">выход</a>
<h1>Админ-панель</h1>
<hr>
<div style="padding:3px 0px; background-color:#55AFE6;">
    <form action="">
    <div style="margin:auto auto; width:55%;">
        <p>
            <button type="submit" name="ctrl" value="1" class="but">Главная</button>
            <button type="submit" name="ctrl" value="2" class="but">Обо мне</button>
            <button type="submit" name="ctrl" value="3" class="but">Услуги</button>
            <button type="submit" name="ctrl" value="4" class="but">Контакты</button>
        </p>
    </div>
    </form>     
</div>
<hr>
<a href="/" style="float:right; margin-right:40px" target="_blank">Открыть сайт в новом окне</a>