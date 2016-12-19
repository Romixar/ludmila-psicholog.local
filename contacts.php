<?php
// проверка авторизован ли пользователь
if(!isset($_SESSION['logged_user'])){
    
    require 'config.php';// подключение кофигурационного файла
    $config = new config();
    //echo 'пользователь незалогинин';
    header('Location: '.$config::HOST_ADDRESS.'login.php');
    //header('Location: http://ludmila-psicholog.ru/login.php');
}
echo 'Управление страницей КОНТАКТЫ';


class ManageContacts{
    
    public $query;
    public function __construct(){
        $this -> query = new DB();// инициализация объектв DB
    }
    
    public function getContacts($table, $asc){
        return $this -> query -> getAllFields($table, $asc);
    }
    
    public function getWorks($table, $asc){
        return $this -> query -> getAllFields($table, $asc);
    }
    
    public function addWork($fields, $table){
        $ids = $this -> query -> getIDs($table);// массив IDs из БД перед ДОБАВЛЕНИЕМ
        
        $insData = array_pop($fields);//возр-ю последний элемент массива, его надо ДОБАВИТЬ в БД
        
        // $fields уже без последнего нового элемента
        $tab = R::getRedBean() -> dispense( $table );// подчеркивание в названии НЕЛЬЗЯ
        
        $tab -> title = $insData['title'];
        
        $id = R::store( $tab );
        if($id){
            $this -> query -> update($fields, $table, $ids);//если добав-сь, отправ-ю на ОБНОВЛ-Е
            return true;
        }return false;
        
    }
    
    
}

$manage = new ManageContacts();


if(isset($_POST['savecontact'])){
    
    $data = $manage -> query -> getData($_POST);
    
    if($data){
        if($manage -> query -> saveData($data, 'userdata'))
            echo '<div class="suc">Личные данные сохранены!</div>';
        else echo '<div class="err">Ошибка при сохранении!</div>';
    }

}

$contacts = $manage -> getContacts('userdata','asc');
// форматируем в красивый вид тел номер
$contacts[0]['phone'] = $manage -> query -> phoneFormat($contacts[0]['phone']);






if(isset($_POST['savework'])){
    
    $data = $manage -> query -> getData($_POST);
    
    if($data){
        $res = $manage -> query -> saveData($data, 'works');
        if($res) echo '<div class="suc">Оказываемые услуги сохранены!</div>';
        else echo '<div class="err">Ошибка при сохранении!</div>';
        if($res === 'abc'){
            if($manage -> addWork($data, 'works'))
                echo '<div class="suc">Новая услуга успешно добавлена!</div>';
            else echo '<div class="err">Ошибка при добавлении!</div>';
        }
    }

}
if(isset($_GET['idwork'])){// отпр на удаление если НАЖАТА кнопка удалить
    if($manage -> query -> delete('works', $_GET['idwork']))
        echo '<div class="suc">Услуга удалёна!</div>';
}

if(isset($_POST['addwork'])){
    $data = $manage -> query -> getData($_POST);// получение двумерного массива
    
    if($data){
        if($manage -> query -> saveData($data, 'works') === 'abc'){//если true, то на вставку нов записи
            
        if($manage -> addWork($data, 'works'))
            echo '<div class="suc">Новая услуга успешно добавлена!</div>';
        else echo '<div class="err">Ошибка при добавлении!</div>';
        }
        $works = $manage -> getWorks('works','asc');// запрос услуг из БД
    }else $works = $manage -> getWorks('works','asc');// запрос отзывов из БД
}else{
    $works = $manage -> getWorks('works','asc');// запрос услуг из БД 
}






?>

<form id="formPrice" action="" method="post">
       
            <table>
               <caption>Оказываемые услуги</caption>
                <thead>
                    <tr>
                        
                        <th>Название услуги</th>
                        <th>&nbsp;</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(!is_array($works)) return false;
                    else{
                        for($i=0; $i<count($works); $i++){
                           
                ?>
                    <tr>
                       
                        <td>
                            <input class="inptitledesc" type="text" name="title_<?= $i ?>" value="<?= $works[$i]['title'] ?>" />
                        </td>
                        <td>
                            <a href="<?= $config::HOST_ADDRESS ?>admin.php?but4=Контакты&idwork=<?= $works[$i]['id'] ?>">Удалить</a>
                        </td>
                    </tr>
                    
                    
                <?php
                        }
                    }
                    if(isset($_POST['addwork'])){
                        $i = count($works);// количество элементов
                ?>
                        <tr> 
                            <td>
                                <input class="inptitledesc" type="text" name="title_<?= $i ?>" />
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                
                
                <?php
                    
                    }
                    
                    ?>
                    <tr>
                       <td colspan="2"><hr/></td>         
                    </tr>
                </tbody>
            </table>
       <input class="buttsave" type="submit" name="addwork" value="Добавить новую услугу">
       <input class="buttsave" type="submit" name="savework" value="Сохранить изменения">
</form>


<form id="formPrice" action="" method="post">
       
            <table>
               <caption>Личные данные</caption>
                <thead>
                    <tr>
                        
                        <th>Название</th>
                        <th>Контакт</th>
                        
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(!is_array($contacts)) return false;
                    else{
                           
                ?>
                    <tr> 
                        <td>    
                            <p>Телефон:</p>
                        </td>
                        <td>
                            <input class="inptitledesc" type="text" name="phone_0" value="<?= $contacts[0]['phone'] ?>" />
                        </td>
                    </tr>
                    
                    <tr> 
                        <td>    
                            <p>Skype:</p>
                        </td>
                        <td>
                            <input class="inptitledesc" type="text" name="skype_0" value="<?= $contacts[0]['skype'] ?>" />
                        </td>
                    </tr>
                    
                    <tr> 
                        <td>    
                            <p>Email:</p>
                        </td>
                        <td>
                            <input class="inptitledesc" type="text" name="email_0" value="<?= $contacts[0]['email'] ?>" />
                        </td>
                    </tr>
                    <tr>
                       <td colspan="2"><hr/></td>         
                    </tr>
                    
                <?php
                        
                    }
                ?>
                
                </tbody>
            </table>
       <input class="buttsave" type="submit" name="savecontact" value="Сохранить изменения">
</form>