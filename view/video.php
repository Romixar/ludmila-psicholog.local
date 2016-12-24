<form id="formPrice" action="" method="post">
       <input class="buttsave" type="submit" name="add-videos" value="Добавить новое видео">
       <input class="buttsave" type="submit" name="save-videos" value="Сохранить изменения">
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

                    if(!is_array($this->data)) return false;
                    else{
                        for($i=0; $i<count($this->data); $i++){
                ?>
                   <tr>
                        <td>
                            <input type="checkbox" <?php if($this->data[$i]['view']==1){ ?>checked<?php } ?> name="view_<?= $i ?>" value="<?= $this->data[$i]['view'] ?>">
                        
                        </td>
                        <td>
                            
                            <input class="inptitledesc" type="text" name="title_<?= $i ?>" value="<?= $this->data[$i]['title'] ?>" /><br/>
                            <p>
                                <input class="inptitledesc" type="text" name="author_<?= $i ?>" value="<?= $this->data[$i]['author'] ?>" />
                            </p>
                            
                            <p>Дата добавления: (дд.мм.гггг)</p>
                            <input type="date" name="dateadd_<?= $i ?>" value="<?= strftime('%Y-%m-%d',$this->data[$i]['dateadd']) ?>" />
                        </td>
                        <td>
                           
                           <p>
                             <input class="inpurl" type="text" name="url_<?= $i ?>" value="http://www.youtube.com/embed/<?= $this->data[$i]['url'] ?>" />  
                           </p>
                           
                           <img src="http://img.youtube.com/vi/<?= $this->data[$i]['url'] ?>/1.jpg" alt="<?= $this->data[$i]['title'] ?>" title="<?= $this->data[$i]['title'] ?>" >
                           
                           
                        </td>
                        <td>
                            <a href="<?= Config::HOST_ADDRESS ?>?ctrl=1&idvid=<?= $this -> data[$i]['id'] ?>">Удалить</a>
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
                    if(isset($_POST['add-videos'])){// проверка нажатия ДОБАВИТЬ
                        $i = count($this->data);// номер для имени поля
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
       <input class="buttsave" type="submit" name="add-videos" value="Добавить новое видео">
       <input class="buttsave" type="submit" name="save-videos" value="Сохранить изменения">
</form>