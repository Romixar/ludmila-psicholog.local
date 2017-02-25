<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title><?= $title ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <link rel="stylesheet" href="../css/style.css"/>
  <script src="../js/jquery-1.11.1.min.js"></script>
  <script src="../js/my.js"></script>
  <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  <![endif]-->
</head>

<body>
   
<a href="/admin/logout" style="float:right; margin-right:40px">выход</a>
<h1>Админ-панель</h1>

    <?= $buttons ?>

<a href="/" style="float:right; margin-right:40px" target="_blank">Открыть сайт в новом окне</a>
   
   <?= $content ?>
   
   
    
    
    
 <div id="footer">
    <?= $footer ?> 
 </div>
  <script src="js/scripts.js"></script>
</body>
</html>