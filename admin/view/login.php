<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="../css/style.css"/>
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/my.js"></script>

			<title>Страница авторизации</title>
</head>
<body>
<h1>Админ-панель</h1>
<hr>
<div class="divauth">
    
    <form class="login" action="" method="post">
    
        <p>
            <p class="white"><strong>Логин:</strong></p>
            <input autofocus class="inpauth" type="text" name="login" value="<?= @$data['login'] ?>" />
        </p>
        <p>
            <p class="white"><strong>Пароль:</strong></p>
            <input class="inpauth" type="password" name="password" value="<?= @$data['password'] ?>" />
        </p>
        
        <button type="submit" name="do_login" value="do_login" >Войти</button>
    
    </form>
                
</div>

<hr>
<a href="/" class='linkadm' target="_blank">Открыть сайт в новом окне</a>