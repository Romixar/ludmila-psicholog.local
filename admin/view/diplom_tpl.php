<form id="formPrice" action="" method="post" enctype="multipart/form-data" >
       <input class="buttsave" type="submit" name="add-<?= $func ?>" value="Добавить новое изображение">
       <input class="buttsave" type="submit" name="save-<?= $func ?>" value="Сохранить изменения">
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
                            
                            <input class="inptitledesc<?php if($data[$i]->title_err){?> error<?php } ?>" type="text" name="title_<?= $i ?>" value="<?= $data[$i]->title ?>" /><br/>
                            
                        </td>
                        <td>
                           
                           <input type="hidden" name="img_<?= $i ?>" value="<?= $data[$i]->img ?>" />
                           
                           <img src="../images/gallery/<?= $data[$i]->img ?>" alt="<?= $data[$i]->title ?>" title="<?= $data[$i]->title ?>" width="200px" >
                           
                           
                        </td>
                        <td>
                            <a href="<?= $url ?>?mod=<?= $func ?>&id=<?= $data[$i]->id ?>">Удалить</a>
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
                    
                    if($open){// проверка нажатия ДОБАВИТЬ
                        $i = count($data);// номер для имени поля
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
       <input class="buttsave" type="submit" name="add-<?= $func ?>" value="Добавить новое изображение">
       <input class="buttsave" type="submit" name="save-<?= $func ?>" value="Сохранить изменения">
</form>