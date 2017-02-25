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
                    
                    if(!is_array($data)) return false;
                    else{
                        for($i=0; $i<count($data); $i++){        
                ?>
                   <tr>
                        <td>
							<input type="hidden" name="id_<?= $i ?>" value="<?= $data[$i]->id ?>" />
                            <input class="inptitledesc<?php if(!empty($data[$i]->title_err)){?> error<?php } ?>" type="text" name="title_<?= $i ?>" value="<?= $data[$i]->title ?>" />
                        </td>
                        <td>
                            <textarea class="inpdesc<?php if(!empty($data[$i]->description_err)){?> error<?php } ?>" name="description_<?= $i ?>" cols="50" rows="8"><?= $data[$i]->description ?></textarea>
                        </td>
                        <td>
                            <a href="<?= Config::HOST_ADDRESS ?>?ctrl=3&id=<?= $this->func ?>_<?= $data[$i]->id ?>">Удалить</a>
                        </td>
                    </tr>
                   
                <?php
                        }
                    }
                ?>
                <?php
                
                    if($this -> open){// проверка нажатия ДОБАВИТЬ УСЛУГУ
                        $i = count($data);// номер для имени поля
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
            
       <input class="buttsave" type="submit" name="add-<?= $this->func ?>" value="Добавить новую услугу">
       <input class="buttsave" type="submit" name="save-<?= $this->func ?>" value="Сохранить изменения">
</form>