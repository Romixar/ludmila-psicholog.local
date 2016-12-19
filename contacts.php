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
	
	public function add($fields, $table){
        $ids = $this -> query -> getIDs($table);// массив IDs из БД перед ДОБАВЛЕНИЕМ
        
        $insData = array_pop($fields);//возр-ю последний элемент массива, его надо ДОБАВИТЬ в БД
        // $fields уже без последнего нового элемента
        $tab = R::getRedBean() -> dispense( $table );// подчеркивание в названии НЕЛЬЗЯ
        
        if($table == 'works'){
			$tab -> title = $insData['title'];
        }        
        $id = R::store( $tab );
        if($id){
            $this -> query -> update($fields, $table, $ids);//если добав-сь, отправ-ю на ОБНОВЛ-Е
            return true;
        }return false;
        
    }
	
	public function checkPost($post){// выбор той или др таблицы при разных POST запросах
        
        if(isset($post['savecontact'])) $this -> table = 'userdata';
        if(isset($post['savework']) || isset($post['addwork'])) $this -> table = 'works';
        
        return $this -> query -> getData($post, false);// получение двумерного массива
    }
    
    
}

$manage = new ManageContacts();

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

if(isset($_GET['idwork'])){// отпр на удаление если НАЖАТА кнопка удалить
    if($manage -> query -> delete('works', $_GET['idwork']))
        echo '<div class="suc">Услуга удалёна!</div>';
}


$contacts = $manage -> query -> getAllFields('userdata','asc');
// форматируем в красивый вид тел номер
$contacts[0]['phone'] = $manage -> query -> phoneFormat($contacts[0]['phone']);
$works = $manage -> query -> getAllFields('works','asc');// запрос услуг из БД 
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