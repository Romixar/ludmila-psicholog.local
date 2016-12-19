<?php
require_once 'settings.php';


$title = 'Практикующий психолог, кандидат наук Пьянкова Людмила';//Заголовок в браузере

class IndexPage extends HTMLPage {
    
    public $query;
    
    public function __construct($param){
        parent::__construct($param);
        $this -> query = new DB(); // класс генерирует запросы к БД
    }
    
    // текст приветствия
    public $mainTxt = '<p>Приветствую! Меня зовут <a href="###">Пьянкова Людмила.</a> Я 
<span>психолог</span>,<span itemprop="description"> специализируюсь на преодолении проблем в сфере детско-родительских, юношеских, семейных и любовных отношений, одиночества, социофобий, компьютерной зависимости</span>. Как специалист развиваюсь в профессиональном сообществе МААП.</p>
<p itemprop="description">Оказываю услуги психолога: веду индивидуальный прием и консультирую пары по вопросам отношений. Провожу психологические тренинги в индивидуальном формате.</p>
<p>Имею 20-летний опыт работы с молодежью, специалист в области образования, в проведении круглых столов, тренингов, семинаров.</p>';

    

    
    // Работа с REDBeanPHP https://www.sitepoint.com/introduction-redbean/
    function getLinkService(){// получаю массив картинок и услуг из БД для главной страницы
        // обращ-е к родит классу PEDBEAN без создания экз (статически)
        //return $arrServ = R::getAll('SELECT title, img FROM services WHERE view_main = ? ', [ 1 ] );
        return $arrServ = R::getAll('SELECT title, img FROM main_services' );
	}
    
    
    // вывод блока ЧЕМ Я МОГУ ПОМОЧЬ (список услуг с описанием)
    function getService(){
        // запрос и получение всего массива услуг из БД
        //return $arrFullServ = R::getAll('SELECT * FROM services WHERE view_main = ? ', [ 0 ]);
        return $arrFullServ = R::getAll('SELECT * FROM services');

        
    }
    
    function getVideos($table,$asc){
        return $this -> query -> getAllFields($table, $asc);
    }
    
    
    public function getPriceTable($table,$asc){
        // запрос таблицы цен на услуги
        return $this -> query -> getAllFields($table, $asc);
    }
    
    public function getTestmon($table, $asc){// запрос на вывод отзывов
        return $this -> query -> getAllFields($table, $asc);
    }
    
    public function getContacts($table, $asc){
        $data = $this -> query -> getAllFields($table, $asc);
        // обработка тел номера для вывода на сайт
        $data[0]['phone'] = $this -> query -> phoneFormat($data[0]['phone']);
        
        return $data;
    }
    
    public function getWorks($table,$asc){
        return $this -> query -> getAllFields($table, $asc);
    }
    
    public function getDiploms($table,$asc){
        return $this -> query -> getAllFields($table, $asc);
    }

    
}

$Page = new IndexPage($title);

$Page->BeginHTML();
$Page->menuHeader($Page -> getContacts('userdata', 'asc')[0]['phone']); // вывод телефона из БД

// вывод приветствия, услуг, отзывов
$Page -> homePage($Page -> mainTxt, $Page -> getLinkService(), $Page -> getTestmon('testmonials','asc'), $Page -> getVideos('videos','asc'));



$Page->about($Page -> getDiploms('diploms','asc')); // страница ОБО МНЕ

$Page->Services($Page->getService(), $Page->getPriceTable('price_table','asc'));//вывод страницы услуг с кр описанием
// вывод таблицы цен

$Page->contacts($Page -> getContacts('userdata', 'asc'), $Page -> getWorks('works','asc'));// запрос контактных данных в масиве и списка услуг для страницы контакты


$Page->EndHTML();








?>