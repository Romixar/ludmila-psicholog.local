<?php
	require_once 'autoload.php';
	
	
	$view = new View();
	
	$view -> display('head');
	
	//$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);// получить путь без GET параметров
	
	


	//new Controller();
	$route = new Router;
	$route -> run();



?>