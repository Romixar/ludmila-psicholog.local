<?php

class Config{
    // испльзовать только НЕ НА ЛОКАЛЬНОМ сервере
    const HOST_ADDRESS = 'http://ludmila-psicholog.local/';// Имя хоста, на котором скрипт
    //const HOST_ADDRESS = 'http://ludmila-psicholog.ru/';
    
    //const DIR_IMG = '/images/gallery/';
    
    //const MAX_FILE_SIZE = 5000000;// в байтах (5 Mb)
	
	
	
	const DB_HOST = 'localhost';
	const DB_NAME = 'psicholog-ludmila';
	const DB_USER = 'root';
	const DB_PASS = '';
	
	public static $routes = [// маршруты с названиями контроллеров для каждой страницы
		
		'ctrl=1' => 'main/all',
		'ctrl=2' => 'about/all',
		'ctrl=3' => 'services/all',
		'ctrl=4' => 'contacts/all',
	
	];
    
    
    
}