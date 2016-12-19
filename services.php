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
    
    public function getAllFields($table, $asc){
        return $this -> query -> getAllFields($table, $asc);
    }
    
    public function saveData($fields,$table){
        return $this -> query -> saveData($fields,$table);
    }
    
    public function saveTextData($data){
        if(is_array($data)){// если не массив то не работает
            if(count($this->getIDs2()) !== count($data)) $this -> addText($data);// ВСТАВИТЬ и ОБНОВИТЬ    
            else $this -> updateText($data);// просто только обновить
            //$this -> updateText($data);// просто только обновить
        }
    }
    
    public function add($fields,$table){
        
        $ids = $this -> query -> getIDs($table);// массив IDs из БД перед ДОБАВЛЕНИЕМ новой записи
        
        $insData = array_pop($fields);//возр-ю последний элемент массива, его надо ДОБАВИТЬ в БД
        // $fields уже без последнего нового элемента
        $tab = R::getRedBean() -> dispense( $table );// подчеркивание в названии НЕЛЬЗЯ
            
        $tab -> title = $insData['title'];
        $tab -> price = $insData['price'];
        $tab -> duration = $insData['duration'];
            
        $id = R::store( $tab );
        
        if($id){
            $this -> query -> update($fields, $table, $ids);//если добав-сь, отправ-ю на ОБНОВЛ-Е
            return true;
        }return false;
    }
    
    public function addText($data,$table){
        
        $ids = $this -> query -> getIDs($table);// массив IDs из БД перед ДОБАВЛЕНИЕМ новой записи
        
        $insData = array_pop($data);//возр-ю последний элемент массива, его надо ДОБАВИТЬ в БД
        // $fields уже без последнего нового элемента
        //$table = R::getRedBean() -> dispense( 'testServixes' );// подчеркивание в названии НЕЛЬЗЯ
        $tab = R::getRedBean() -> dispense( $table );// подчеркивание в названии НЕЛЬЗЯ
            
        $tab -> title = $insData['title'];
        $tab -> description = $insData['description'];
        
        $id = R::store( $tab );
        if($id){
            $this -> query -> update($data, $table, $ids);//если добав-сь, отправ-ю на ОБНОВЛ-Е
            return true;
        }return false;
        
    }

    public function getData($data){// создание двумерного массива из полученной формы
        return $this -> query -> getData($data);
    }
    
    public function delete($table,$id){
        return $this -> query -> delete($table,$id);
    }


    
}

$manage = new manageServices();
// работа с таблицепй цен
if(isset($_POST['saveserv'])){// НАЖАТА кнопка сохранить
    $data = $manage -> getData($_POST);// получение двумерного массива
    if($data){
        $res = $manage -> saveData($data,'price_table');// запись в БД по нажатию на СОХРАНИТЬ
        
        if($res)
            echo '<div class="suc">Таблица цен на услуги сохранена!</div>';
        else echo '<div class="err">Ошибка при сохранении!</div>';

        if($res === 'abc'){
            if($manage -> add($data, 'price_table'))
                echo '<div class="suc">Новая услуга добавлена!</div>';
            else echo '<div class="err">Ошибка при добавлении!</div>';
        }
    }
}
if(isset($_GET['id'])){// отпр на удаление если НАЖАТА кнопка удалить
    if($manage -> delete('price_table',$_GET['id']))
        echo '<div class="suc">Услуга из таблицы цен удалена</div>';
}
if(isset($_POST['addserv'])){// если нажата ДОБАВИТЬ УСЛУГУ
    $data = $manage -> getData($_POST);// получение двумерн массива из формы
    
    if($data){
        //если придет abc то на вставку нов записи
        if($manage -> saveData($data, 'price_table') === 'abc'){
            if($manage -> add($data, 'price_table'))
                echo '<div class="suc">Новая услуга добавлена!</div>';
            else echo '<div class="err">Ошибка при добавлении!</div>';
        }
        $arrPriceTab = $manage -> getAllFields('price_table', 'asc');// запрос отзывов из БД
    }else $arrPriceTab = $manage -> getAllFields('price_table', 'asc');// запрос отзывов из БД
}else{
    $arrPriceTab = $manage -> getAllFields('price_table', 'asc');// список услуг загружаю из БД
}
// работа с описаниями услуг
if(isset($_POST['savedesc'])){
    $data = $manage -> getData($_POST);// получение двумерного массива
    if($data){
        
        $res = $manage -> saveData($data,'services');// запись в БД по нажатию на СОХРАНИТЬ
        
        if($res)
            echo '<div class="suc">Названия и описания услуг сохранены!</div>';
        else echo '<div class="err">Ошибка при сохранении!</div>';

        if($res === 'abc'){
            if($manage -> addText($data, 'services'))
                echo '<div class="suc">Новая услуга добавлена!</div>';
            else echo '<div class="err">Ошибка при добавлении!</div>';
        }
    }
}
if(isset($_GET['iddesc'])){
    if($manage -> delete('services',$_GET['iddesc']))
        echo '<div class="suc">Описание услуги удалено!</div>';
}
if(isset($_POST['adddesc'])){
    $data = $manage -> getData($_POST);// получение двумерн массива из формы
    
    if($data){
        if($manage -> saveData($data, 'services') === 'abc'){//если придет abc то на вставку нов записи
            if($manage -> addText($data, 'services'))
                echo '<div class="suc">Новая услуга добавлена!</div>';
            else echo '<div class="err">Ошибка при добавлении!</div>';
        }
        $arrFullServ = $manage -> getAllFields('services', 'asc');// запрос отзывов из БД
        
    }else $arrFullServ = $manage -> getAllFields('services', 'asc');// запрос отзывов из БД
}else{
    $arrFullServ = $manage -> getAllFields('services', 'asc');//получаю список услуг с описанием
}
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