<form id="formPrice" action="" method="post">
       <input class="buttsave" type="submit" name="add-testmonials" value="Добавить новый отзыв">
       <input class="buttsave" type="submit" name="save-testmonials" value="Сохранить изменения">
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
                           
                           <input type="checkbox" <?php if($this->data[$i]['view']==1){ ?>checked<?php } ?> name="view_<?= $i ?>" value="<?= $this->data[$i]['view'] ?>">
                        
                        </td>
                        <td>
                            <input class="inptitledesc" type="text" name="name_<?= $i ?>" value="<?= $this->data[$i]['name'] ?>" />
                            <p>Дата добавления: (дд.мм.гггг)</p>
                            <input type="date" name="dateadd_<?= $i ?>" value="<?= strftime('%Y-%m-%d',$this->data[$i]['dateadd']) ?>" />
                        
                        </td>
                        <td>
                            
                            <textarea class="prewtestm" name="head_<?= $i ?>" cols="50" rows="3"><?= $this->data[$i]['head'] ?></textarea>
                            <br/>
                            <textarea class="inpdesc" name="body_<?= $i ?>" cols="50" rows="8"><?= $this->data[$i]['body'] ?></textarea>
                        </td>
                        <td>
                            <a href="<?= Config::HOST_ADDRESS ?>?ctrl=1&id=<?= $this->data[$i]['id'] ?>">Удалить</a>
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
                    if(isset($_POST['add-testmonials'])){// проверка нажатия ДОБАВИТЬ УСЛУГУ
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
       <input class="buttsave" type="submit" name="add-testmonials" value="Добавить новый отзыв">
       <input class="buttsave" type="submit" name="save-testmonials" value="Сохранить изменения">
</form>