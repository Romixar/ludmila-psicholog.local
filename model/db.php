<?php

//require 'rb.php';// подключение RedBeanPHP библиотеки
require '../config.php';// подключение кофигурационного файла

//if($config::HOST_ADDRESS == 'http://ludmila-psicholog.local/')//выбор БД к которой подключиться
    //R::setup( 'mysql:host=localhost;dbname=psicholog-ludmila','root', '' );
//else R::setup( 'mysql:host=localhost;dbname=vh190779_ludmila-psicholog','vh190779_rom', 'ERFKCgmu' );


//session_start();// переменная сессии доступна на страницах где подключен этот файл



class DB{
    
	private $dbh;// объект подключения к БД
	private $class = 'stdClass';// здесь будет имя класс, который вызывает данный
	protected static $table;// название таблицы в каждом дочернем классе
	public $data = [];// здесь будут счвойства дочерних объектов (поля таблицы)
	
	
	
    public function __construct(){
		
		$this -> dbh = new PDO('mysql:host='.config::DB_HOST.';dbname='.config::DB_NAME,config::DB_USER, config::DB_PASS);
		
		if(!$this -> dbh) die('Отсутствует подключение к базе данных (((');
		
		$this -> class = get_called_class();// получаем имя класс, вызвывающего объект DB
		
	}
    
	protected function query($sql, $params = false, $ins = false){// обработка запроса в PDO
	
	//print_r($params);
	
		if(!$params){
			
			$sth = $this -> dbh -> query($sql);// исполнение простого запроса
			//return $sth -> fetch(PDO::FETCH_OBJ);// возвращаю результат в ввиде объекта std
			//return $sth -> fetchObject($this -> class);// возвращаю результат в ввиде объекта нужного класса
			return $sth -> fetchAll(PDO::FETCH_CLASS, $this -> class);// возвращает объект
			//return $sth -> fetchAll();// возвращает массив
		} 
		else {// выборка из базы по ключу 
			
			$sth = $this -> dbh -> prepare($sql);
			$sth -> execute($params);// отправляю на исполнение
			
			if($ins) return $this -> dbh -> lastInsertId();
			
			return $sth -> fetchObject($this -> class);// возвращаю масив результат
			
		}
		
		
		
	}
	
	
	
	
	
	
	
	public function selectAll(){
		
		$sql = 'SELECT * FROM '.static::$table;
		
		return $this -> query($sql);
		//var_dump($this -> query($sql));
	}
	
	
	public function update($data, $params){
		
		$sql = 'UPDATE `'.static::$table.'` SET '.implode(', ',$data).' WHERE `id` = :id';
		return $this -> query($sql, $params);
		
	}
	
	public function insert($data){
		
		$cols = array_keys($data);// названия столбцов
		$vals = [];// здесь будут места для подготовленного запроса именнованные переменные
		$params = [];// здесь будут параметры для подстановки
		
		foreach($data as $key => $val){
			
			$key = str_replace('`','',$key);
			$vals[] = ':'.$key;// именнованная переменная для подстановки
			if(is_numeric($val)) $params[':'.$key] = $val;// если число, то без кавычек
			else $params[':'.$key] = "'".$val."'";
			
		}
		
		$sql = 'INSERT INTO `'.static::$table.'` ('.implode(', ',$cols).') VALUES ('.implode(', ',$vals).')';
		
		return $this -> query($sql, $params, true);
		
	}
	
	
    
	
	public function __set($k, $v){// добавляем свойства дочернего объекта во внутр-й массив
		// в нашем случае при вызове news создаём ему свойства
		$this -> data[$k] = $v;
	}
	
	public function __get($k){
		return $this -> data[$k];
	}
	
	public function __isset($k){
		return isset($this -> data[$k]);
	}
	
	
	
	
	
}

//$db = new DB();

//$res = $db -> select('SELECT * FROM `price_table` WHERE `id` = :id', [':id' => 3]);

//$res = $db -> update('price_table', ['`title` = "проверка_000"','`price` = 123'], [':id' => 59]);

//$res = $db -> insert('price_table', ['`title`' => 'КРУТТООООО!','`price`' => 3210.63]);

// print_r($res);die;

// foreach($res as $row){
	// print_r($row);
// }













