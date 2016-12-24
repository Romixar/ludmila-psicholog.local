<form id="formPrice" action="" method="post" enctype="multipart/form-data" >
       <input class="buttsave" type="submit" name="add-diploms" value="Добавить новое изображение">
       <input class="buttsave" type="submit" name="save-diploms" value="Сохранить изменения">
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
                            
                        </td>
                        <td>
                           
                           <input type="hidden" name="img_<?= $i ?>" value="<?= $this->data[$i]['img'] ?>" />
                           
                           <img src="images/gallery/<?= $this->data[$i]['img'] ?>" alt="<?= $this->data[$i]['title'] ?>" title="<?= $this->data[$i]['title'] ?>" width="200px" >
                           
                           
                        </td>
                        <td>
                            <a href="<?= Config::HOST_ADDRESS ?>ctrl=4&idimg=<?= $this->data[$i]['id'] ?>">Удалить</a>
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
                    if(isset($_POST['add-diploms'])){// проверка нажатия ДОБАВИТЬ
                        $i = count($this->data);// номер для имени поля
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
       <input class="buttsave" type="submit" name="add-diploms" value="Добавить новое изображение">
       <input class="buttsave" type="submit" name="save-diploms" value="Сохранить изменения">
</form>