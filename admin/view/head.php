<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="../css/style.css"/>
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/my.js"></script>
    
	<?php//if(is_array($title)){ ?>
		<?php// foreach($title as $key => $val){ ?>
			<title>Управление страницей <?php// $val ?></title>
		<?php// } ?>
	<?php //} ?>
</head>
<body>
<a href="/admin/logout" style="float:right; margin-right:40px">выход</a>
<h1>Админ-панель</h1>
<hr>
<div style="padding:3px 0px; background-color:#55AFE6;">
<!--    <form action="">-->
    <div style="margin:auto auto; width:55%;">
        <p>
           
           <?php foreach(Config::$routes as $k => $v){
            
                    if($k !== 'logout'){
            
            ?>
           
               <button type="submit" name="ctrl" value="<?= $k ?>" class="but"><?= $v[1] ?></button>    
               
           <?php    }
            } ?>
           
        </p>
    </div>
<!--    </form>     -->
</div>
<hr>
<a href="/" style="float:right; margin-right:40px" target="_blank">Открыть сайт в новом окне</a>