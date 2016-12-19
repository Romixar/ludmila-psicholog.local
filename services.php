<?php

// проверка авторизован ли пользователь
if(!isset($_SESSION['logged_user'])){
    require 'config.php';// подключение кофигурационного файла
    $config = new Config();
    //echo 'пользователь незалогинин';
    header('Location: '.$config::HOST_ADDRESS.'login.php');
    //header('Location: http://ludmila-psicholog.ru/login.php');
}

echo 'Управление страницей УСЛУГИ';

class manageServices{
    public $query;
    public function __construct(){
        $this -> query = new DB();// инициализация объектв DB
    }
    

	public function add($fields, $table){
        $ids = $this -> query -> getIDs($table);// массив IDs из БД перед ДОБАВЛЕНИЕМ
        
        $insData = array_pop($fields);//возр-ю последний элемент массива, его надо ДОБАВИТЬ в БД
        // $fields уже без последнего нового элемента
        $tab = R::getRedBean() -> dispense( $table );// подчеркивание в названии НЕЛЬЗЯ
        
        if($table == 'services'){
			$tab -> title = $insData['title'];
			$tab -> description = $insData['description'];
        }else{
            $tab -> title = $insData['title'];
			$tab -> price = $insData['price'];
			$tab -> duration = $insData['duration'];
        }
        
        $id = R::store( $tab );
        if($id){
            $this -> query -> update($fields, $table, $ids);//если добав-сь, отправ-ю на ОБНОВЛ-Е
            return true;
        }return false;
        
    }

	public function checkPost($post){// выбор той или др таблицы при разных POST запросах
        
        if(isset($post['saveserv']) || isset($post['addserv'])) $this -> table = 'price_table';
        if(isset($post['savedesc']) || isset($post['adddesc'])) $this -> table = 'services';
        
        return $this -> query -> getData($post, false);// получение двумерного массива
    }


    
}

$manage = new manageServices();

if(count($_POST) != 0){
    // проверка какая форма отправлена и получение двумерного массива
    $data = $manage -> checkPost($_POST);

    if($data){
        // проверка либо только обновить, либо добавить и обновить записи
        $res = $manage -> query -> saveData($data, $manage -> table);
        
        if($res) echo $manage -> query -> checkErrors($_POST, true);// успешное собщение
        else echo $manage -> query -> checkErrors($_POST, false);// возвращает собщение ошибки
            
        if($res === 'abc'){
            if($manage -> add($data, $manage -> table))
                echo $manage -> query -> checkErrors($_POST, true, $res);// успешное сообщение
            else echo $manage -> query -> checkErrors($_POST, false, $res);// собщение ошибки
        }
    }
}


if(isset($_GET['id'])){// отпр на удаление если НАЖАТА кнопка удалить
    if($manage -> query -> delete('price_table',$_GET['id']))
        echo '<div class="suc">Услуга из таблицы цен удалена</div>';
}

if(isset($_GET['iddesc'])){
    if($manage -> query -> delete('services',$_GET['iddesc']))
        echo '<div class="suc">Описание услуги удалено!</div>';
}


$arrPriceTab = $manage -> query -> getAllFields('price_table', 'asc');// запрос отзывов из БД
$arrFullServ = $manage -> query -> getAllFields('services', 'asc');// запрос видеороликов из БД

?>
<form id="formPrice" action="" method="post">
            <table>
               <caption>Цены на услуги психолога</caption>
                <thead>
                    <tr>
                        <th>Услуга</th>
                        <th>Стоимость</th>
                        <th>Продолжительность</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(!is_array($arrPriceTab)) return false;
                    else{
                        for($i=0; $i<count($arrPriceTab); $i++){        
                ?>
                   <tr>
                        <td>
                            <input class="inptitle" type="text" name="title_<?= $i ?>" value="<?= $arrPriceTab[$i]['title'] ?>" />
                        
                        </td>
                        <td>
                            <input class="inpprice" type="text" name="price_<?= $i ?>" value="<?= $arrPriceTab[$i]['price'] ?>" />
                        <span>&nbsp;руб.</span>
                        </td>
                        <td>
                            <input class="inpprice" type="text" name="duration_<?= $i ?>" value="<?= $arrPriceTab[$i]['duration'] ?>" />
                        </td>
                        <td>
<!--                            <a href="http://ludmila-psicholog.local/admin.php?but3=Услуги&id=<?php// $arrPriceTab[$i]['id'] ?>">Удалить</a>-->
                            <a href="<?= $config::HOST_ADDRESS ?>admin.php?but3=Услуги&id=<?= $arrPriceTab[$i]['id'] ?>">Удалить</a>
                        </td>
                    </tr>
                <?php
                        }
                    }
                ?>
                <?php
                    if(isset($_POST['addserv'])){// проверка нажатия ДОБАВИТЬ УСЛУГУ
                        $i = count($arrPriceTab);// номер для имени поля
                    ?>
                        <tr>
                            <td>
                                <input class="inptitle" type="text" name="title_<?= $i ?>" />
                            </td>
                            <td>
                                <input class="inpprice" type="text" name="price_<?= $i ?>" />
                                <span>&nbsp;руб.</span>
                            </td>
                            <td>
                                <input class="inpprice" type="text" name="duration_<?= $i ?>" />
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>      
                <?php
                    }
                ?>
                </tbody>
            </table>
       <input class="buttsave" type="submit" name="addserv" value="Добавить новую услугу">
       <input class="buttsave" type="submit" name="saveserv" value="Сохранить изменения">
</form>
<form id="formPrice" action="" method="post">
           
            <table>
               <caption>Чем я могу помочь?</caption>
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    
                    if(!is_array($arrFullServ)) return false;
                    else{
                        for($i=0; $i<count($arrFullServ); $i++){        
                ?>
                   <tr>
                        <td>
                            <input class="inptitledesc" type="text" name="title_<?= $i ?>" value="<?= $arrFullServ[$i]['title'] ?>" />
                        </td>
                        <td>
                            <textarea class="inpdesc" name="description_<?= $i ?>" cols="50" rows="8"><?= $arrFullServ[$i]['description'] ?></textarea>
                        </td>
                        <td>
<!--                            <a href="http://ludmila-psicholog.local/admin.php?but3=Услуги&iddesc=<?php// $arrFullServ[$i]['id'] ?>">Удалить</a>-->
                            <a href="<?= $config::HOST_ADDRESS ?>admin.php?but3=Услуги&iddesc=<?= $arrFullServ[$i]['id'] ?>">Удалить</a>
                        </td>
                    </tr>
                   
                <?php
                        }
                    }
                ?>
                <?php
                
                    if(isset($_POST['adddesc'])){// проверка нажатия ДОБАВИТЬ УСЛУГУ
                        $i = count($arrFullServ);// номер для имени поля
                    ?>
                     
                        <tr>
                            <td>
                                <input class="inptitledesc" type="text" name="title_<?= $i ?>" />
                            </td>
                            <td>
                                <textarea class="inpdesc" name="description_<?= $i ?>" cols="50" rows="8"></textarea>
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                        
                <?php
                    }
                ?>
  
                </tbody>
            </table>
            
       <input class="buttsave" type="submit" name="adddesc" value="Добавить новую услугу">
       <input class="buttsave" type="submit" name="savedesc" value="Сохранить изменения">
</form>