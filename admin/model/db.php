<?php

//require 'rb.php';// подключение RedBeanPHP библиотеки
//require '../config.php';// подключение кофигурационного файла

//if($config::HOST_ADDRESS == 'http://ludmila-psicholog.local/')//выбор БД к которой подключиться
    //R::setup( 'mysql:host=localhost;dbname=psicholog-ludmila','root', '' );
//else R::setup( 'mysql:host=localhost;dbname=vh190779_ludmila-psicholog','vh190779_rom', 'ERFKCgmu' );


//session_start();// переменная сессии доступна на страницах где подключен этот файл



class DB{
    
	public $dbh;// объект подключения к БД
	private $class = 'stdClass';// здесь будет имя класс, который вызывает данный
	protected static $table;// название таблицы в каждом дочернем классе
	public $data = [];// здесь будут свойства дочерних объектов (поля таблицы)
	
	
	
    public function __construct(){
		
		$this -> dbh = new PDO('mysql:host='.config::DB_HOST.';dbname='.config::DB_NAME,config::DB_USER, config::DB_PASS);
		
		if(!$this -> dbh) die('Отсутствует подключение к базе данных (((');
		
		//$this -> class = get_called_class();// получаем имя класс, вызвывающего этот объект DB
		
	}
    
	protected function query($sql, $params = false){// обработка запроса в PDO
	
		if(!$params){
			
			$sth = $this -> dbh -> query($sql);// исполнение простого запроса
			//return $sth -> fetch(PDO::FETCH_OBJ);// возвращаю результат в ввиде объекта std
			//return $sth -> fetchObject($this -> class);// возвращаю результат в ввиде объекта нужного класса
			
			return $sth -> fetchAll(PDO::FETCH_CLASS, $this -> class);// возвращает объект
			//return $sth -> fetch(PDO::FETCH_LAZY);// возвращает объект
			//return $sth -> fetchAll();// возвращает массив
		} 
		else{// запросы в БД с параметрами 
			
			$sth = $this -> dbh -> prepare($sql);
			
			$sth -> execute($params);// отправляю на исполнение
			
            
            $resObj = $sth -> fetchObject($this -> class);

            $lastID = $this -> dbh -> lastInsertId();

            if($lastID != 0) return $lastID;// если есть результат, значит был INSERT
            
            if($resObj === false) return false;// значит был UPDATE или DELETE
            
            //if(!empty($lastID)) return $lastID;// значит был INSERT
            return $resObj;// значит просто SELECT с параметрами
            
            
            //if($resObj !== false) return $this -> dbh -> lastInsertId();
            //return $resObj;// значит просто UPDATE или DELETE
            
            
			//return $sth -> fetchObject($this -> class);// возвращаю массив результат
			//return $sth -> fetch();// возвращаю масив результат
			
		}
		
		
		
	}
	
	
	
	
	
	public function countRow(){// узнать сколько записей в таблице БД
		
		$sql = 'SELECT COUNT(*) FROM `'.static::$table.'`';
		
		$sth = $this -> dbh -> query($sql);// исполнение простого запроса
		
		return $sth -> fetch()[0];// получаю массив с ответом
		
	}
	
	public function checkID($id){
		
		$sql = 'SELECT * FROM `'.static::$table.'` WHERE `id` = :id';
		
		$params = [':id' => $id];
		
//        echo $sql.'<br/>';
//        print_r($params);die;
        $obj = $this -> query($sql, $params);
        
		if($obj !== false){
            // $this -> delete возвращает FALSE при успешном удалении
            if($this -> delete($params)) return false;
		    return true;
//			throw new MyException('Ничего не найдено для удаления в базе!');// вброс исключения
//			return true;
        }
           
		
		
		
	}
	
	public function delete($params){
		
		$sql = 'DELETE FROM `'.static::$table.'` WHERE `id` = :id';
		
		return $this -> query($sql, $params);
        
	}
	
	public function selectAll(){
		
		$sql = 'SELECT * FROM `'.static::$table.'`';
		
		return $this -> query($sql);
		//var_dump($this -> query($sql));
	}
	
	
	public function update($data, $params){
		
		$sql = 'UPDATE `'.static::$table.'` SET '.implode(', ',$data).' WHERE `id` = :id';
		
//		echo $sql.'<br/>';
//		print_r($params);
		return $this -> query($sql, $params);
		
	}
	// $vals=false,
	public function insert($cols, $params){
		
		$sql = 'INSERT INTO `'.static::$table.'` ('.implode(', ',$cols).') VALUES (:'.implode(', :',$cols).')';

//		echo $sql.'<br/>';
//		print_r($params);
		return $this -> query($sql, $params);
		
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










