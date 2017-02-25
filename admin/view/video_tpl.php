<form id="formPrice" action="" method="post">
       <input class="buttsave" type="submit" name="add-<?= $func ?>" value="Добавить новое видео">
       <input class="buttsave" type="submit" name="save-<?= $func ?>" value="Сохранить изменения">
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

                    if(!is_array($data)) return false;
                    else{
                        for($i=0; $i<count($data); $i++){
                ?>
                   <tr>
                        <td>
							<input type="hidden" name="id_<?= $i ?>" value="<?= $data[$i]->id ?>" />
                            <input type="checkbox" <?php if($data[$i]->view==1){ ?>checked<?php } ?> name="view_<?= $i ?>" value="<?= $data[$i]->view ?>">
                        
                        </td>
                        <td>
                            
                            <input class="inptitledesc<?php if(!empty($data[$i]->title_err)){?> error<?php } ?>" type="text" name="title_<?= $i ?>" value="<?= $data[$i]->title ?>" /><br/>
                            <p>
                                <input class="inptitledesc<?php if(!empty($data[$i]->author_err)){?> error<?php } ?>" type="text" name="author_<?= $i ?>" value="<?= $data[$i]->author ?>" />
                            </p>
                            
                            <p>Дата добавления: (дд.мм.гггг)</p>
                            <input class="<?php if(!empty($data[$i]->dateadd_err)){?> error<?php } ?>" type="text" name="dateadd_<?= $i ?>" value="<?= strftime('%Y-%m-%d',$data[$i]->dateadd) ?>" />
                        </td>
                        <td>
                           
                           <p>
                             <input class="inpurl<?php if(!empty($data[$i]->url_err)){?> error<?php } ?>" type="text" name="url_<?= $i ?>" value="http://www.youtube.com/embed/<?= $data[$i]->url ?>" />  
                           </p>
                           
                           <img src="http://img.youtube.com/vi/<?= $data[$i]->url ?>/1.jpg" alt="<?= $data[$i]->title ?>" title="<?= $data[$i]->title ?>" >
                           
                           
                        </td>
                        <td>
                            <a href="<?= Config::HOST_ADDRESS ?>?ctrl=1&id=<?= $func ?>_<?= $data[$i]->id ?>">Удалить</a>
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
                    if($this -> open){// проверка нажатия ДОБАВИТЬ
                        $i = count($data);// номер для имени поля
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
       <input class="buttsave" type="submit" name="add-<?= $func ?>" value="Добавить новое видео">
       <input class="buttsave" type="submit" name="save-<?= $func ?>" value="Сохранить изменения">
</form>