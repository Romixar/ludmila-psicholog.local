<?php
require 'db.php';
// проверка авторизован ли пользователь
if(isset($_SESSION['logged_user'])){
    //echo 'пользователь незалогинин';
    header('Location: '.$config::HOST_ADDRESS.'admin.php?but1=Главная');
    //header('Location: http://ludmila-psicholog.ru/admin.php?but1=Главная');
}
header("Content-Type: text/html; charset=utf-8");


$data = $_POST;
if(isset($data['do_login'])){// если была нажата кнопка
    
    $err = [];// здесь будет сообщения об ошибках
    
    // поиск в таблице users введённого логина
    $user = R::findOne('users','login = ?',array($data['login']));
    
    if($user){// если найден в БД
        // логин существует
        // версия для 5.6 и выше
        //if(password_verify($data['password'],$user -> password)){
        if(md5($data['password']) === $user -> password){// берет пароль из строки с логином
            // все хорошо логиним пользователя
            $_SESSION['logged_user'] = $user;// сохраняем в сессии весь объект user
            // редирект на главную админ-панельки
            header('Location: '.$config::HOST_ADDRESS.'admin.php?but1=Главная');
            //header('Location: http://ludmila-psicholog.ru/admin.php?but1=Главная');
            
        }else{
            $err[] = 'Неверный пароль';
        }
        
        
    }else{
        
        $err[] = 'Пользователь с таким логином не существует';
    }
    
}

?>
<head>
   <link rel="stylesheet" href="css/style.css"/>
   <title>Страница авторизации</title>
</head>
<h1>Админ-панель</h1>
<?php
    if(!empty($err)){// если сообщения об ошибках есть, то выводим первое
        echo '<div class="err">'.array_shift($err).'</div>';
    }
?>
<hr>
<div class="divauth">
    
    <form class="login" action="login.php" method="post">
    
        <p>
            <p class="white"><strong>Логин:</strong></p>
            <input autofocus class="inpauth" type="text" name="login" value="<?= @$data['login'] ?>" />
        </p>
        <p>
            <p class="white"><strong>Пароль:</strong></p>
            <input class="inpauth" type="password" name="password" value="<?= @$data['password'] ?>" />
        </p>
        
        <button type="submit" name="do_login">Войти</button>
    
    </form>
                
</div>

<hr>
<a href="/" class='linkadm' target="_blank">Открыть сайт в новом окне</a>
