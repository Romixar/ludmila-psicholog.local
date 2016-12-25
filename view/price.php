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
                    if(!is_array($this->data)) return false;
                    else{
                        for($i=0; $i<count($this->data); $i++){        
                ?>
                   <tr>
                        <td>
                            <input class="inptitle" type="text" name="title_<?= $i ?>" value="<?= $this->data[$i]['title'] ?>" />
                        
                        </td>
                        <td>
                            <input class="inpprice" type="text" name="price_<?= $i ?>" value="<?= $this->data[$i]['price'] ?>" />
                        <span>&nbsp;руб.</span>
                        </td>
                        <td>
                            <input class="inpprice" type="text" name="duration_<?= $i ?>" value="<?= $this->data[$i]['duration'] ?>" />
                        </td>
                        <td>
                            <a href="<?= Config::HOST_ADDRESS ?>?ctrl=3&id=<?= $this->func ?>_<?= $this->data[$i]['id'] ?>">Удалить</a>
                        </td>
                    </tr>
                <?php
                        }
                    }
                ?>
                <?php
                    if(isset($_POST["add-".$this->data['func']])){// проверка нажатия ДОБАВИТЬ УСЛУГУ
                        $i = count($this->data);// номер для имени поля
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
       <input class="buttsave" type="submit" name="add-<?= $this->func ?>" value="Добавить новую услугу">
       <input class="buttsave" type="submit" name="save-<?= $this->func ?>" value="Сохранить изменения">
</form>