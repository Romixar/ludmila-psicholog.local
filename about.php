<?php
// проверка авторизован ли пользователь
if(!isset($_SESSION['logged_user'])){
    
    require 'config.php';// подключение кофигурационного файла
    $config = new config();
    //echo 'пользователь незалогинин';
    header('Location: '.$config::HOST_ADDRESS.'login.php');
    //header('Location: http://ludmila-psicholog.ru/login.php');
}
echo 'Управление страницей ОБО МНЕ';

class ManageAbout{
    
    public $query;
    
    public function __construct(){
        $this -> query = new DB(); // класс генерирует запросы к БД
    }
    
    public function addImg($fields, $table){
        
        $ids = $this -> query -> getIDs($table);// массив IDs из БД перед ДОБАВЛЕНИЕМ
        
        $insData = array_pop($fields);//возр-ю последний элемент массива, его надо ДОБАВИТЬ в БД
        // $fields уже без последнего нового элемента
        $tab = R::getRedBean() -> dispense( $table );// подчеркивание в названии НЕЛЬЗЯ
        
        $tab -> view = $insData['view'];
        $tab -> title = $insData['title'];
        $tab -> img = $insData['img'];
        
        $id = R::store( $tab );
        if($id){
            $this -> query -> update($fields, $table, $ids);//если добав-сь, отправ-ю на ОБНОВЛ-Е
            return true;
        }return false;
        
    }
    
    public function uploadIMG($files){
        
        if($files['name'] != ''){
            if(is_uploaded_file($files['tmp_name'])){
                // тщательная проверка загружаемого файла
                $res = $this -> query -> uploadIMG($files, Config::MAX_FILE_SIZE, Config::DIR_IMG);
                if($res){
                    echo '<div class="suc">Файл успешно загружен!</div>';
                    return $res;
                }else return $res;
            }
        }else{
            echo '<div class="err">Файл не выбран!</div>';
            return false;
        }
        
    }
    
    
    
    
    
    
    
    
}


$manage = new ManageAbout();



if(isset($_POST['saveimg']) || isset($_POST['addimg'])){
    // если выбран файл для загрузки
    if(isset($_FILES['imgupload'])) $filename = $manage -> uploadIMG($_FILES['imgupload']);
    
    $data = $manage -> query -> getData($_POST, true);// получение двумерного массива
    // добавляю в конец массива имя загруженного файла
    if($filename) $data[count($data)-1]['img'] = $filename;
        
    if($data){
        $res = $manage -> query -> saveData($data, 'diploms');

        if($res && !isset($_POST['addimg']))
            echo '<div class="suc">Изображения дипломов сохранены!</div>';
        //else echo '<div class="err">Ошибка при сохранении!</div>';

        if(($res === 'abc') && $filename){
            if($manage -> addImg($data, 'diploms'))
                echo '<div class="suc">Новое изображение успешно добавлено!</div>';
            else echo '<div class="err">Ошибка при добавлении!</div>';
        }
    }
}


if(isset($_GET['idimg'])){// отпр на удаление если НАЖАТА кнопка удалить
    // поиск в таблице конкретной строки (получаю объект)
    $tab = $manage -> query -> findRow('diploms','id',$_GET['idimg']);
    
    if($tab) $manage -> query -> deleteFile($tab -> img);// удаление файла
    
    if($manage -> query -> delete('diploms', $_GET['idimg']))// удаление строки в таблице
        echo '<div class="suc">Диплом удалён!</div>';
}


//if(isset($_POST['addimg'])){
//    // если выбран файл для загрузки
//    if(isset($_FILES['imgupload'])) $filename = $manage -> uploadIMG($_FILES['imgupload']);
//    
//    $data = $manage -> query -> getData($_POST, true);// получение двумерного массива
//    
//    if($filename){// если есть загруженный файл, если нет - добавления не будет
//        // добавляю в конец массива имя загруженного файла
//        $data[count($data)-1]['img'] = $filename;
//        
//        if($data){
//            $res = $manage -> query -> saveData($data, 'diploms');
//            
//            if($res === 'abc'){// на вставку нов записи
//
//            if($manage -> addImg($data, 'diploms'))
//                echo '<div class="suc">Новый диплом добавлен!</div>';
//            else echo '<div class="err">Ошибка при добавлении!</div>';
//            }
//        }
//    }
//}

$diploms = $manage -> query -> getAllFields('diploms','asc');// запрос изо из БД




?>
<form id="formPrice" action="" method="post" enctype="multipart/form-data" >
       <input class="buttsave" type="submit" name="addimg" value="Добавить новое изображение">
       <input class="buttsave" type="submit" name="saveimg" value="Сохранить изменения">
            <table>
               <caption>Дипломы и сертификаты</caption>
                <thead>
                    <tr>
                        <th>Вкл.</th>
                        <th>Название</th>
                        <th>Изображение</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(!is_array($diploms)) return false;
                    else{
                        for($i=0; $i<count($diploms); $i++){        
                ?>
                   <tr>
                        <td>
                            <input type="checkbox" <?php if($diploms[$i]['view']==1){ ?>checked<?php } ?> name="view_<?= $i ?>" value="<?= $diploms[$i]['view'] ?>">
                        
                        </td>
                        <td>
                            
                            <input class="inptitledesc" type="text" name="title_<?= $i ?>" value="<?= $diploms[$i]['title'] ?>" /><br/>
                            
                        </td>
                        <td>
                           
                           <input type="hidden" name="img_<?= $i ?>" value="<?= $diploms[$i]['img'] ?>" />
                           
                           <img src="images/gallery/<?= $diploms[$i]['img'] ?>" alt="<?= $diploms[$i]['title'] ?>" title="<?= $diploms[$i]['title'] ?>" width="200px" >
                           
                           
                        </td>
                        <td>
                            <a href="<?= $config::HOST_ADDRESS ?>admin.php?but2=Обо мне&idimg=<?= $diploms[$i]['id'] ?>">Удалить</a>
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
                    if(isset($_POST['addimg'])){// проверка нажатия ДОБАВИТЬ
                        $i = count($diploms);// номер для имени поля
                    ?>
                        <tr>
                            <td>
                            <input type="checkbox" checked name="view_<?= $i ?>" />
                        
                            </td>
                            <td>
                                
                                <input class="inptitledesc" type="text" name="title_<?= $i ?>" /><br/>
                            
                            </td>
                            <td>
                               <p>
                                 <input class="inpurl" type="file" name="imgupload" />  
                               </p>
                           
                           
                           
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
       <input class="buttsave" type="submit" name="addimg" value="Добавить новое изображение">
       <input class="buttsave" type="submit" name="saveimg" value="Сохранить изменения">
</form>