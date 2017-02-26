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
                    if(!is_array($data)) return false;
                    else{
                        for($i=0; $i<count($data); $i++){        
                ?>
                   <tr>
                        <td>
							<input type="hidden" name="id_<?= $i ?>" value="<?= $data[$i]->id ?>" />
                            <input class="inptitle<?php if(!empty($data[$i]->title_err)){?> error<?php } ?>" type="text" name="title_<?= $i ?>" value="<?= $data[$i]->title ?>" />
                        
                        </td>
                        <td>
                            <input class="inpprice<?php if(!empty($data[$i]->price_err)){?> error<?php } ?>" type="text" name="price_<?= $i ?>" value="<?= $data[$i]->price ?>" />
                        <span>&nbsp;руб.</span>
                        </td>
                        <td>
                            <input class="inpprice<?php if(!empty($data[$i]->duration_err)){?> error<?php } ?>" type="text" name="duration_<?= $i ?>" value="<?= $data[$i]->duration ?>" />
                        </td>
                        <td>
                            <a href="<?= Config::HOST_ADDRESS ?>?ctrl=3&id=<?= $func ?>_<?= $data[$i]->id ?>">Удалить</a>
                        </td>
                    </tr>
                <?php
                        }
                    }
                ?>
                <?php
                    if($open){// проверка нажатия ДОБАВИТЬ УСЛУГУ
                        $i = count($data);// номер для имени поля
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
       <input class="buttsave" type="submit" name="add-<?= $func ?>" value="Добавить новую услугу">
       <input class="buttsave" type="submit" name="save-<?= $func ?>" value="Сохранить изменения">
</form>