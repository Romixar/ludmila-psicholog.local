<form id="formPrice" action="" method="post">
       
            <table>
               <caption>Оказываемые услуги</caption>
                <thead>
                    <tr>
                        
                        <th>Название услуги</th>
                        <th>&nbsp;</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                <?php
					
                    for($i=0; $i<count($this->data); $i++){      
                ?>
                    <tr>
                       
                        <td>
                            <input type="hidden" name="id_<?= $i ?>" value="<?= $this->data[$i]->id ?>" />
							<input class="inptitledesc<?php if(!empty($this->data[$i]->title_err)){?> error<?php } ?>" type="text" name="title_<?= $i ?>" value="<?= $this->data[$i]->title ?>" />
							
                        </td>
                        <td>
                            <a href="<?= Config::HOST_ADDRESS ?>?ctrl=4&id=<?= $this->func ?>_<?= $this->data[$i]->id ?>">Удалить</a>
                        </td>
                    </tr>
                    
                    
                <?php
                    }
                    
                    if($this -> open){
                        $i = count($this->data);// количество элементов
                ?>
                        <tr> 
                            <td>
                                <input class="inptitledesc" type="text" name="title_<?= $i ?>" />
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                
                
                <?php
                    
                    }
                    
                    ?>
                    <tr>
                       <td colspan="2"><hr/></td>         
                    </tr>
                </tbody>
            </table>
       <input class="buttsave" type="submit" name="add-<?= $this->func ?>" value="Добавить новую услугу">
       <input class="buttsave" type="submit" name="save-<?= $this->func ?>" value="Сохранить изменения">
</form>