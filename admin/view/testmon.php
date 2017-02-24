<form id="formPrice" action="" method="post">
       <input class="buttsave" type="submit" name="add-<?= $this->func ?>" value="Добавить новый отзыв">
       <input class="buttsave" type="submit" name="save-<?= $this->func ?>" value="Сохранить изменения">
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
				
                    if(!is_array($this->data)) return false;
                    else{
                        for($i=0; $i<count($this->data); $i++){
                ?>
                    <tr>
                        <td>
                           <input type="hidden" name="id_<?= $i ?>" value="<?= $this->data[$i]->id ?>" />
                           <input type="checkbox" <?php if($this->data[$i]->view==1){ ?>checked<?php } ?> name="view_<?= $i ?>" value="<?= $this->data[$i]->view ?>">
                        
                        </td>
                        <td>
                            <input class="inptitledesc<?php if(!empty($this->data[$i]->name_err)){?> error<?php } ?>" type="text" name="name_<?= $i ?>" value="<?= $this->data[$i]->name ?>" />
                            <p>Дата добавления: (дд.мм.гггг)</p>
                            <input class="<?php if(!empty($this->data[$i]->dateadd_err)){?> error<?php } ?>" type="date" name="dateadd_<?= $i ?>" value="<?= strftime('%Y-%m-%d',$this->data[$i]->dateadd) ?>" />
                        
                        </td>
                        <td>
                            
                            <textarea class="prewtestm<?php if(!empty($this->data[$i]->head_err)){?> error<?php } ?>" name="head_<?= $i ?>" cols="50" rows="3"><?= $this->data[$i]->head ?></textarea>
                            <br/>
                            <textarea class="inpdesc<?php if(!empty($this->data[$i]->body_err)){?> error<?php } ?>" name="body_<?= $i ?>" cols="50" rows="8"><?= $this->data[$i]->body ?></textarea>
                        </td>
                        <td>
                            <a href="<?= Config::HOST_ADDRESS ?>?ctrl=1&id=<?= $this->func ?>_<?= $this->data[$i]->id ?>">Удалить</a>
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
                    if($this -> open){// проверка нажатия ДОБАВИТЬ УСЛУГУ
                        $i = count($this->data);// номер для имени поля
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
       <input class="buttsave" type="submit" name="add-<?= $this->func ?>" value="Добавить новый отзыв">
       <input class="buttsave" type="submit" name="save-<?= $this->func ?>" value="Сохранить изменения">
</form>