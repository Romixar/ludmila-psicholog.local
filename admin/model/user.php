<?php

class User extends DB{
    
    
    protected static $table = 'user';// название таблицы
    public static $checkBox = false;// флаг наличия чекбоксов
	
	public $tmpl = 'login';// шаблон для вывоа данной таблицы
    
    public $auth = ''; //  будет ID авторизованного пользователя
    
    
    
    
    
    
    
    
    
    
}







?>