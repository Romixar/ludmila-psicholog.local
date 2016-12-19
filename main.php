<?php

// проверка авторизован ли пользователь
if(!isset($_SESSION['logged_user'])){
    
    require 'config.php';// подключение кофигурационного файла
    $config = new config();
    //echo 'пользователь незалогинин';
    header('Location: '.$config::HOST_ADDRESS.'login.php');
    //header('Location: http://ludmila-psicholog.ru/login.php');
}
echo 'Управление страницей ГЛАВНАЯ';

class manageMain{
    
    public $query;
    public $table = '';// здесь будет название таблицы БД
    
    public function __construct(){
        $this -> query = new DB(); // класс генерирует запросы к БД
    }
    
    public function add($fields, $table){
        $ids = $this -> query -> getIDs($table);// массив IDs из БД перед ДОБАВЛЕНИЕМ
        
        $insData = array_pop($fields);//возр-ю последний элемент массива, его надо ДОБАВИТЬ в БД
        // $fields уже без последнего нового элемента
        $tab = R::getRedBean() -> dispense( $table );// подчеркивание в названии НЕЛЬЗЯ
        
        if($table == 'videos'){        
            $tab -> view = $insData['view'];
            $tab -> title = $insData['title'];
            $tab -> author = $insData['author'];
            $tab -> dateadd = $insData['dateadd'];
            $tab -> url = $insData['url'];
        }else{
            $tab -> view = $insData['view'];
            $tab -> name = $insData['name'];
            $tab -> dateadd = $insData['dateadd'];
            $tab -> head = $insData['head'];
            $tab -> body = $insData['body'];
        }
        
        $id = R::store( $tab );
        if($id){
            $this -> query -> update($fields, $table, $ids);//если добав-сь, отправ-ю на ОБНОВЛ-Е
            return true;
        }return false;
        
    }
    
    public function checkPost($post){// выбор той или др таблицы при разных POST запросах
        
        if(isset($post['savetestmon']) || isset($post['addtestmon'])) $this -> table = 'testmonials';
        if(isset($post['savevid']) || isset($post['addvid'])) $this -> table = 'videos';
        
        return $this -> query -> getData($post, true);// получение двумерного массива
    }

    
}

$manage = new manageMain();


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



if(isset($_GET['id'])){// отпр на удаление отзыв если НАЖАТА кнопка удалить
    if($manage -> query -> delete('testmonials', $_GET['id']))
        echo '<div class="suc">Отзыв удалён!</div>';
    //else echo '<div class="err">Ошибка при удалении отзыва!</div>';
}


if(isset($_GET['idvid'])){// отпр на удаление видео если НАЖАТА кнопка удалить
    if($manage -> query -> delete('videos', $_GET['idvid']))
        echo '<div class="suc">Видеоролик удалён!</div>';
}




$testmonials = $manage -> query -> getAllFields('testmonials', 'asc');// запрос отзывов из БД
$arrvideos = $manage -> query -> getAllFields('videos', 'asc');// запрос видеороликов из БД
?>


<form id="formPrice" action="" method="post">
       <input class="buttsave" type="submit" name="addvid" value="Добавить новое видео">
       <input class="buttsave" type="submit" name="savevid" value="Сохранить изменения">
            <table>
               <caption>Лента видеороликов</caption>
                <thead>
                    <tr>
                        <th>Вкл.</th>
                        <th>Название / Автор / Дата добавления</th>
                        <th>Ссылка на видеоролик / Картинка видеоролика</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(!is_array($arrvideos)) return false;
                    else{
                        for($i=0; $i<count($arrvideos); $i++){        
                ?>
                   <tr>
                        <td>
                            <input type="checkbox" <?php if($arrvideos[$i]['view']==1){ ?>checked<?php } ?> name="view_<?= $i ?>" value="<?= $arrvideos[$i]['view'] ?>">
                        
                        </td>
                        <td>
                            
                            <input class="inptitledesc" type="text" name="title_<?= $i ?>" value="<?= $arrvideos[$i]['title'] ?>" /><br/>
                            <p>
                                <input class="inptitledesc" type="text" name="author_<?= $i ?>" value="<?= $arrvideos[$i]['author'] ?>" />
                            </p>
                            
                            <p>Дата добавления: (дд.мм.гггг)</p>
                            <input type="date" name="dateadd_<?= $i ?>" value="<?= strftime('%Y-%m-%d',$arrvideos[$i]['dateadd']) ?>" />
                        </td>
                        <td>
                           
                           <p>
                             <input class="inpurl" type="text" name="url_<?= $i ?>" value="http://www.youtube.com/embed/<?= $arrvideos[$i]['url'] ?>" />  
                           </p>
                           
                           <img src="http://img.youtube.com/vi/<?= $arrvideos[$i]['url'] ?>/1.jpg" alt="<?= $arrvideos[$i]['title'] ?>" title="<?= $arrvideos[$i]['title'] ?>" >
                           
                           
                        </td>
                        <td>
                            <a href="<?= $config::HOST_ADDRESS ?>admin.php?but1=Главная&idvid=<?= $arrvideos[$i]['id'] ?>">Удалить</a>
                        </td>
                    </tr>
                    <tr>
                       <td colspan="4"><hr/></td>         
                    </tr>
                <?php
                        }
                    }
                ?>
                <?php
                    if(isset($_POST['addvid'])){// проверка нажатия ДОБАВИТЬ
                        $i = count($arrvideos);// номер для имени поля
                    ?>
                        <tr>
                            <td>
                            <input type="checkbox" checked name="view_<?= $i ?>" />
                        
                        </td>
                        <td>
                            
                            <input class="inptitledesc" type="text" name="title_<?= $i ?>" /><br/>
                            <p>
                                <input class="inptitledesc" type="text" name="author_<?= $i ?>" />
                            </p>
                            
                            <p>Дата добавления: (дд.мм.гггг)</p>
                            <input type="date" name="dateadd_<?= $i ?>" value="<?= strftime('%Y-%m-%d',time()) ?>" />
                        </td>
                        <td>
                           
                           <p>
                             <input class="inpurl" type="text" name="url_<?= $i ?>" />  
                           </p>
                           
                           <img src="http://img.youtube.com/vi/xxx/1.jpg" >
                           
                           
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
       <input class="buttsave" type="submit" name="addvid" value="Добавить новое видео">
       <input class="buttsave" type="submit" name="savevid" value="Сохранить изменения">
</form>
<form id="formPrice" action="" method="post">
       <input class="buttsave" type="submit" name="addtestmon" value="Добавить новый отзыв">
       <input class="buttsave" type="submit" name="savetestmon" value="Сохранить изменения">
            <table>
               <caption>Отзывы</caption>
                <thead>
                    <tr>
                        <th>Вкл.</th>
                        <th>Имя / Дата добавления</th>
                        <th>Вступление / Содержание отзыва</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(!is_array($testmonials)) return false;
                    else{
                        for($i=0; $i<count($testmonials); $i++){        
                ?>
                    <tr>
                        <td>
                           
                           <input type="checkbox" <?php if($testmonials[$i]['view']==1){ ?>checked<?php } ?> name="view_<?= $i ?>" value="<?= $testmonials[$i]['view'] ?>">
                        
                        </td>
                        <td>
                            <input class="inptitledesc" type="text" name="name_<?= $i ?>" value="<?= $testmonials[$i]['name'] ?>" />
                            <p>Дата добавления: (дд.мм.гггг)</p>
                            <input type="date" name="dateadd_<?= $i ?>" value="<?= strftime('%Y-%m-%d',$testmonials[$i]['dateadd']) ?>" />
                        
                        </td>
                        <td>
                            
                            <textarea class="prewtestm" name="head_<?= $i ?>" cols="50" rows="3"><?= $testmonials[$i]['head'] ?></textarea>
                            <br/>
                            <textarea class="inpdesc" name="body_<?= $i ?>" cols="50" rows="8"><?= $testmonials[$i]['body'] ?></textarea>
                        </td>
                        <td>
                            <a href="<?= $config::HOST_ADDRESS ?>admin.php?but1=Главная&id=<?= $testmonials[$i]['id'] ?>">Удалить</a>
                        </td>
                    </tr>
                    <tr>
                       <td colspan="4"><hr/></td>         
                    </tr>
                    
                <?php
                        }
                    }
                ?>
                <?php
                    if(isset($_POST['addtestmon'])){// проверка нажатия ДОБАВИТЬ УСЛУГУ
                        $i = count($testmonials);// номер для имени поля
                    ?>
                        <tr>
                            <td>
                           
                               <input type="checkbox" checked name="view_<?= $i ?>" />

                            </td>
                            <td>
                                <input class="inptitledesc" type="text" name="name_<?= $i ?>" />
                                <p>Дата добавления: (дд.мм.гггг)</p>
<!--                            date('d-m-Y')    -->
                                <input type="date" name="dateadd_<?= $i ?>" value="<?= date('Y-m-d') ?>" />

                            </td>
                            <td>
                                <textarea class="prewtestm" name="head_<?= $i ?>" cols="50" rows="3"></textarea>
                                <br/>
                                <textarea name="body_<?= $i ?>" cols="50" rows="8"></textarea>
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
       <input class="buttsave" type="submit" name="addtestmon" value="Добавить новый отзыв">
       <input class="buttsave" type="submit" name="savetestmon" value="Сохранить изменения">
</form>